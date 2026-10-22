<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug', 'nav_label', 'meta_title', 'meta_description', 'keywords',
        'eyebrow', 'h1', 'intro', 'banner_path', 'banner_url', 'banner_alt', 'sort_order',
    ];

    public function bannerUrl(): string
    {
        return Media::url($this->banner_path, $this->banner_url);
    }
}
