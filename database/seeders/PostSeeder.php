<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load scraped blog posts
        $jsonPath = base_path('blog_posts.json');
        if (!file_exists($jsonPath)) {
            $this->command->error('blog_posts.json not found!');
            return;
        }

        $posts = json_decode(file_get_contents($jsonPath), true);

        foreach ($posts as $postData) {
            // Create the post
            $post = Post::create([
                'title' => $postData['title'],
                'sub_heading' => $postData['sub_heading'],
                'content' => $postData['content'],
                'author' => $postData['author'],
                'featured_image' => $postData['featured_image'] ? '/storage/blog_images/' . basename($postData['featured_image']) : null,
                'category_id' => null,
                'published_at' => $postData['created_at'] ? date('Y-m-d H:i:s', strtotime($postData['created_at'])) : now(),
            ]);

            // Attach tags
            if (!empty($postData['tags'])) {
                foreach ($postData['tags'] as $tagName) {
                    $tag = Tag::where('name', $tagName)->first();
                    if ($tag) {
                        $post->tags()->attach($tag->id);
                    }
                }
            }
        }

        $this->command->info('Created ' . count($posts) . ' posts');
    }
}
