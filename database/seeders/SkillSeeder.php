<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Habilidades específicas para barberos y estilistas
        $skills = [
            [
                'name' => 'Corte Clásico',
            ],
            [
                'name' => 'Corte Moderno',
            ],
            [
                'name' => 'Degradado Americano',
            ],
            [
                'name' => 'Degradado Bajo',
            ],
            [
                'name' => 'Degradado Alto',
            ],
            [
                'name' => 'Tijeras y Navaja',
            ],
            [
                'name' => 'Afeitado Tradicional',
            ],
            [
                'name' => 'Arreglo de Barba',
            ],
            [
                'name' => 'Perfilado de Barba',
            ],
            [
                'name' => 'Corte con Diseño',
            ],
            [
                'name' => 'Corte de Niños',
            ],
            [
                'name' => 'Corte Ejecutivo',
            ],
            [
                'name' => 'Tratamiento Capilar',
            ],
            [
                'name' => 'Lavado y Secado',
            ],
            [
                'name' => 'Masaje Capilar',
            ],
            [
                'name' => 'Aplicación de Productos',
            ],
            [
                'name' => 'Coloración de Cabello',
            ],
            [
                'name' => 'Mechas y Reflejos',
            ],
            [
                'name' => 'Tratamiento Anticaspa',
            ],
            [
                'name' => 'Tratamiento Anticaída',
            ],
        ];

        foreach ($skills as $skill) {
            \App\Models\Skill::create($skill);
        }

        // Crear algunas habilidades adicionales con el factory
        \App\Models\Skill::factory(10)->create();
    }
}
