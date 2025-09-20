<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar todos los servicios con status 'draft' a 'inactive'
        DB::table('services')
            ->where('status', 'draft')
            ->update(['status' => 'inactive']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // En caso de rollback, cambiar de vuelta a draft (opcional)
        DB::table('services')
            ->where('status', 'inactive')
            ->where('updated_at', '>=', now()->subMinutes(5)) // Solo los recién actualizados
            ->update(['status' => 'draft']);
    }
};
