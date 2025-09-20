<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', // Nombre
        'last_name',  // Apellido
        'email',      // Correo electrónico
        'phone_number', // Teléfono
        'image',      // Imagen
        'status',     // Estado
    ];
}
