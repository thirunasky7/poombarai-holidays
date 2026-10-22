<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourStop extends Model
{
    protected $fillable = ['tour_plan_id', 'destination_id', 'day_label', 'sort_order'];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(TourPlan::class, 'tour_plan_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
