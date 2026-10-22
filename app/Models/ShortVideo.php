<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class ShortVideo extends Model
{
    protected $table = 'shorts';

    protected $fillable = [
        'title', 'caption', 'video_path', 'poster_path', 'button_label', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function videoUrl(): string
    {
        return Media::url($this->video_path);
    }

    public function posterUrl(): string
    {
        return Media::url($this->poster_path);
    }
}
