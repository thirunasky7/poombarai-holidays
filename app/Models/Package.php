<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function imageSrc(): string
    {
        return Media::url($this->image_path, $this->image_url);
    }
}
