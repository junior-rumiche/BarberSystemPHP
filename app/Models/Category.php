<?php

namespace App\Models;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cover_image',
        'slug',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            // Solo regenerar el slug si el nombre cambió y el slug está vacío o es igual al slug del nombre anterior
            if ($category->isDirty('name')) {
                $originalName = $category->getOriginal('name');
                $originalSlug = Str::slug($originalName);
                
                // Si el slug actual es igual al slug del nombre original, actualizarlo
                if (empty($category->slug) || $category->slug === $originalSlug) {
                    $category->slug = Str::slug($category->name);
                }
            }
        });
    }
}
