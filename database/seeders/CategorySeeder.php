<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Bisnis', 'slug' => 'bisnis', 'icon' => '💼', 'description' => 'Template untuk kebutuhan bisnis profesional'],
            ['name' => 'Portfolio', 'slug' => 'portfolio', 'icon' => '🎨', 'description' => 'Template untuk showcase karya dan portfolio'],
            ['name' => 'E-commerce', 'slug' => 'e-commerce', 'icon' => '🛒', 'description' => 'Template untuk toko online dan e-commerce'],
            ['name' => 'Restoran', 'slug' => 'restoran', 'icon' => '🍽️', 'description' => 'Template untuk restoran dan bisnis kuliner'],
            ['name' => 'Blog', 'slug' => 'blog', 'icon' => '📝', 'description' => 'Template untuk blog personal dan profesional'],
            ['name' => 'Event', 'slug' => 'event', 'icon' => '🎪', 'description' => 'Template untuk event dan konferensi'],
            ['name' => 'Travel', 'slug' => 'travel', 'icon' => '✈️', 'description' => 'Template untuk bisnis travel dan wisata'],
            ['name' => 'Education', 'slug' => 'education', 'icon' => '🎓', 'description' => 'Template untuk institusi pendidikan'],
        ];

        foreach ($categories as $category) {
            // Gunakan updateOrCreate untuk menghindari duplicate
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}