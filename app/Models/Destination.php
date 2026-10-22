<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Destination extends Model
{
    protected $fillable = [
        'slug', 'name', 'name_ta', 'description', 'best_time', 'time_needed',
        'popular', 'image_path', 'image_url', 'image_alt', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class);
    }

    public function imageSrc(): string
    {
        return Media::url($this->image_path, $this->image_url);
    }
}
