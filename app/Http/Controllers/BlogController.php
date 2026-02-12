<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->with(['category', 'tags']);
        
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        $posts = $query->latest('published_at')->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(Post $post)
    {
        $post->load(['category', 'tags']);
        return view('blog.show', compact('post'));
    }

    public function category(Category $category)
    {
        $posts = $category->posts()
            ->published()
            ->with(['category', 'tags'])
            ->latest('published_at')
            ->paginate(20);

        return view('blog.category', compact('category', 'posts'));
    }

    public function tag(Tag $tag)
    {
        $posts = $tag->posts()
            ->published()
            ->with(['category', 'tags'])
            ->latest('published_at')
            ->paginate(20);

        return view('blog.tag', compact('tag', 'posts'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $posts = Post::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('sub_heading', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->with(['category', 'tags'])
            ->latest('published_at')
            ->paginate(20);

        return view('blog.search', compact('posts', 'query'));
    }
}
