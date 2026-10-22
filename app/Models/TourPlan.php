<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourPlan extends Model
{
    protected $fillable = ['slug', 'title', 'duration', 'summary', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function stops(): HasMany
    {
        return $this->hasMany(TourStop::class)->orderBy('sort_order');
    }
}
