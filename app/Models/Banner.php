<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'text', 'button_label', 'button_url',
        'image_path', 'image_url', 'image_alt', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function imageSrc(): string
    {
        return Media::url($this->image_path, $this->image_url);
    }
}
