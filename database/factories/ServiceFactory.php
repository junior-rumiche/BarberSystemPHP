<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        
        return [
            'category_id' => \App\Models\Category::factory(),
            'name' => ucwords($name),
            'description' => fake()->paragraph(2),
            'slug' => \Illuminate\Support\Str::slug($name),
            'status' => fake()->randomElement(['active', 'inactive']),
            'price' => fake()->randomFloat(2, 15, 150),
            'time_estimate_minutes' => fake()->randomElement([15, 30, 45, 60, 90, 120]),
        ];
    }
}
