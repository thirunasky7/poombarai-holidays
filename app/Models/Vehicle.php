<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'slug', 'name', 'example', 'seats', 'ac', 'price_label', 'blurb', 'rates',
        'image_path', 'image_url', 'image_alt', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'rates' => 'array',
        'ac' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function imageSrc(): string
    {
        return Media::url($this->image_path, $this->image_url);
    }
}
