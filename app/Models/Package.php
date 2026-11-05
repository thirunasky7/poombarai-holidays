<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'slug', 'name', 'duration', 'fare', 'price_label', 'summary',
        'image_path', 'image_url', 'image_alt', 'featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class)->orderBy('destinations.sort_order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PackageImage::class)->orderBy('sort_order');
    }

    public function imageSrc(): string
    {
        return Media::url($this->image_path, $this->image_url);
    }

    public function galleryFrames(): array
    {
        $frames = [];
        if ($this->imageSrc()) {
            $frames[] = ['src' => $this->imageSrc(), 'alt' => $this->image_alt ?: $this->name];
        }
        foreach ($this->images as $image) {
            $src = $image->src();
            if (! $src) {
                continue;
            }
            $frames[] = ['src' => $src, 'alt' => $image->alt ?: $this->name];
        }

        return $frames;
    }
}
