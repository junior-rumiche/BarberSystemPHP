<?php

namespace App\Models;

use App\ServiceStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'cover_image_url',
        'slug',
        'status',
        'price',
        'time_estimate_minutes',
    ];

    protected $casts = [
        'status' => ServiceStatusEnum::class,
        'price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });

        static::updating(function ($service) {
            if ($service->isDirty('name') && empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function serviceImages()
    {
        return $this->hasMany(ServiceImage::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'S/ ' . number_format($this->price, 2);
    }

    public function getFormattedTimeAttribute(): string
    {
        if (!$this->time_estimate_minutes) {
            return 'No especificado';
        }
        
        $hours = intval($this->time_estimate_minutes / 60);
        $minutes = $this->time_estimate_minutes % 60;
        
        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}min";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}min";
        }
    }
}
