<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías específicas para servicios de barbería
        $categories = [
            [
                'name' => 'Cortes de Cabello',
                'description' => 'Servicios de corte de cabello para hombres, incluyendo cortes clásicos, modernos y personalizados.',
                'slug' => 'cortes-de-cabello',
                'status' => 'active',
            ],
            [
                'name' => 'Afeitado y Barba',
                'description' => 'Servicios de afeitado tradicional, arreglo de barba y cuidado facial masculino.',
                'slug' => 'afeitado-y-barba',
                'status' => 'active',
            ],
            [
                'name' => 'Tratamientos Capilares',
                'description' => 'Tratamientos especializados para el cuidado del cabello y cuero cabelludo.',
                'slug' => 'tratamientos-capilares',
                'status' => 'active',
            ],
            [
                'name' => 'Servicios Premium',
                'description' => 'Servicios exclusivos y de alta gama para una experiencia completa.',
                'slug' => 'servicios-premium',
                'status' => 'active',
            ],
            [
                'name' => 'Productos',
                'description' => 'Venta de productos para el cuidado del cabello y barba.',
                'slug' => 'productos',
                'status' => 'draft',
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }

        // Crear algunas categorías adicionales con el factory
        \App\Models\Category::factory(5)->create();
    }
}
