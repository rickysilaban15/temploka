<?php

namespace Database\Factories;

use App\Models\Template;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    protected $model = Template::class;

    public function definition()
    {
        $categories = Category::pluck('id')->toArray();
        
        return [
            'name' => $this->faker->words(3, true) . ' Template',
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->numberBetween(100000, 500000),
            'thumbnail' => 'https://picsum.photos/500/300?random=' . $this->faker->numberBetween(1, 100),
            'preview_images' => json_encode([
                'https://picsum.photos/800/600?random=' . $this->faker->numberBetween(1, 100),
                'https://picsum.photos/800/600?random=' . $this->faker->numberBetween(101, 200)
            ]),
            'features' => json_encode(['Responsive Design', 'SEO Friendly', 'Fast Loading', 'Easy Customization']),
            'category_id' => $this->faker->randomElement($categories),
            'is_featured' => $this->faker->boolean(30),
            'is_active' => true,
        ];
    }
}