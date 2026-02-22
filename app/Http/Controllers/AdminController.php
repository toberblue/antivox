<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function posts(Request $request)
    {
        $query = Post::with('category');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }
        
        $posts = $query->orderBy('published_at', 'desc')->paginate(50)->withQueryString();
        
        return view('admin.posts', compact('posts'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        
        return view('admin.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sub_heading' => 'nullable|string',
            'content' => 'required',
            'author' => 'required|string|max:255',
            'published_at' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $originalName = $file->getClientOriginalName();
            $file->storeAs('blog_images', $originalName, 'public');
            $imagePath = '/storage/blog_images/' . $originalName;
        }

        $post = Post::create([
            'title' => $validated['title'],
            'sub_heading' => $validated['sub_heading'],
            'content' => $validated['content'],
            'author' => $validated['author'],
            'published_at' => $validated['published_at'],
            'category_id' => $validated['category_id'],
            'featured_image' => $imagePath,
        ]);

        if (isset($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        return redirect()->route('admin.posts')->with('success', 'Post created successfully');
    }

    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $post->load(['category', 'tags']);
        
        return view('admin.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sub_heading' => 'nullable|string',
            'content' => 'required',
            'author' => 'required|string|max:255',
            'published_at' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|max:2048'
        ]);

        $updateData = [
            'title' => $validated['title'],
            'sub_heading' => $validated['sub_heading'],
            'content' => $validated['content'],
            'author' => $validated['author'],
            'published_at' => $validated['published_at'],
            'category_id' => $validated['category_id'],
        ];

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $originalName = $file->getClientOriginalName();
            $file->storeAs('blog_images', $originalName, 'public');
            $updateData['featured_image'] = '/storage/blog_images/' . $originalName;
        }

        $post->update($updateData);

        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('admin.edit', $post)->with('success', 'Post updated successfully');
    }

    public function createTag(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name'
        ]);

        $tag = Tag::create([
            'name' => $validated['name'],
            'slug' => \Illuminate\Support\Str::slug($validated['name'])
        ]);

        return response()->json($tag);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts')->with('success', 'Post deleted successfully');
    }

    public function share(Post $post)
    {
        // Generate platform-specific image paths with fallback logic
        $baseImage = $post->featured_image;
        $filename = pathinfo($baseImage, PATHINFO_FILENAME);
        $extension = pathinfo($baseImage, PATHINFO_EXTENSION);
        
        // Helper function to check if file exists
        $getImagePath = function($platform) use ($filename, $extension, $baseImage) {
            // Look for platform-specific image in blog_images directory
            $platformImage = '/storage/blog_images/' . $filename . '_' . $platform . '.' . $extension;
            $instagramImage = '/storage/blog_images/' . $filename . '_instagram.' . $extension;
            
            // Check if platform-specific image exists
            if (file_exists(public_path($platformImage))) {
                return $platformImage;
            }
            // Fallback to Instagram (square) version
            if ($platform !== 'instagram' && file_exists(public_path($instagramImage))) {
                return $instagramImage;
            }
            // Fallback to original image
            return $baseImage;
        };
        
        $images = [
            'facebook' => $getImagePath('facebook'),
            'instagram' => $getImagePath('instagram'),
            'linkedin' => $getImagePath('linkedin'),
        ];
        
        return view('admin.share', compact('post', 'images'));
    }
}
