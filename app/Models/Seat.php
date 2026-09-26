<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    protected $guarded = [];

    public function stand(): BelongsTo
    {
        return $this->belongsTo(Stand::class);
    }
}