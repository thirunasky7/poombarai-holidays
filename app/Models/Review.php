<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['stars', 'quote', 'guest_name', 'detail', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];
}
