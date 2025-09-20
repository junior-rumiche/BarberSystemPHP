<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skills = [
            'Corte de cabello',
            'Manicura',
            'Pedicura',
            'Tinte',
            'Peinado',
            'Barba',
            'Cejas',
            'Masaje capilar',
            'Tratamiento facial',
            'Maquillaje'
        ];

        return [
            'name' => fake()->unique()->randomElement($skills),
        ];
    }
}
