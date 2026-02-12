<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Cancer', 'order' => 1],
            ['name' => 'Columns', 'order' => 2],
            ['name' => 'Real estate', 'order' => 3],
            ['name' => 'Limericks', 'order' => 4],
            ['name' => 'Boatbuilding', 'order' => 5],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'order' => $category['order'],
            ]);
        }
    }
}
