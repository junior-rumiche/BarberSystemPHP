<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener categorías existentes
        $categories = \App\Models\Category::where('status', 'active')->get();
        
        if ($categories->isEmpty()) {
            $this->command->warn('No hay categorías activas. Ejecuta primero CategorySeeder.');
            return;
        }

        // Servicios específicos para barbería
        $services = [
            [
                'category_id' => $categories->where('name', 'Cortes de Cabello')->first()?->id ?? $categories->first()->id,
                'name' => 'Corte Clásico',
                'description' => 'Corte tradicional con tijeras y máquina, incluye lavado y peinado.',
                'slug' => 'corte-clasico',
                'status' => 'active',
                'price' => 25.00,
                'time_estimate_minutes' => 30,
            ],
            [
                'category_id' => $categories->where('name', 'Cortes de Cabello')->first()?->id ?? $categories->first()->id,
                'name' => 'Corte Moderno',
                'description' => 'Cortes actuales y tendencias, con acabado profesional.',
                'slug' => 'corte-moderno',
                'status' => 'active',
                'price' => 35.00,
                'time_estimate_minutes' => 45,
            ],
            [
                'category_id' => $categories->where('name', 'Afeitado y Barba')->first()?->id ?? $categories->first()->id,
                'name' => 'Afeitado Tradicional',
                'description' => 'Afeitado con navaja tradicional, incluye toallas calientes y bálsamo.',
                'slug' => 'afeitado-tradicional',
                'status' => 'active',
                'price' => 20.00,
                'time_estimate_minutes' => 25,
            ],
            [
                'category_id' => $categories->where('name', 'Afeitado y Barba')->first()?->id ?? $categories->first()->id,
                'name' => 'Arreglo de Barba',
                'description' => 'Perfilado y arreglo de barba con máquina y tijeras.',
                'slug' => 'arreglo-de-barba',
                'status' => 'active',
                'price' => 15.00,
                'time_estimate_minutes' => 20,
            ],
            [
                'category_id' => $categories->where('name', 'Tratamientos Capilares')->first()?->id ?? $categories->first()->id,
                'name' => 'Tratamiento Anticaspa',
                'description' => 'Tratamiento especializado para eliminar la caspa y fortalecer el cuero cabelludo.',
                'slug' => 'tratamiento-anticaspa',
                'status' => 'active',
                'price' => 40.00,
                'time_estimate_minutes' => 60,
            ],
            [
                'category_id' => $categories->where('name', 'Servicios Premium')->first()?->id ?? $categories->first()->id,
                'name' => 'Paquete Completo',
                'description' => 'Corte + Afeitado + Tratamiento capilar + Masaje relajante.',
                'slug' => 'paquete-completo',
                'status' => 'active',
                'price' => 80.00,
                'time_estimate_minutes' => 120,
            ],
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }

        // Crear algunos servicios adicionales con el factory
        \App\Models\Service::factory(8)->create([
            'category_id' => $categories->random()->id,
        ]);
    }
}
