<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load all unique tags from scraped data
        $jsonPath = base_path('blog_posts.json');
        if (!file_exists($jsonPath)) {
            $this->command->error('blog_posts.json not found!');
            return;
        }

        $posts = json_decode(file_get_contents($jsonPath), true);
        $allTags = [];
        
        foreach ($posts as $post) {
            if (!empty($post['tags'])) {
                $allTags = array_merge($allTags, $post['tags']);
            }
        }

        $uniqueTags = array_unique($allTags);
        sort($uniqueTags);

        foreach ($uniqueTags as $tagName) {
            Tag::create([
                'name' => $tagName,
                'slug' => Str::slug($tagName),
            ]);
        }

        $this->command->info('Created ' . count($uniqueTags) . ' tags');
    }
}
