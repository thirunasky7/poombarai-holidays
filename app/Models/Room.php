<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'slug', 'name', 'type', 'price_label', 'occupancy', 'location',
        'summary', 'amenities', 'featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'amenities' => 'array',
        'featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class)->orderBy('sort_order');
    }

    public function cover(): ?RoomImage
    {
        return $this->images->first();
    }
}
