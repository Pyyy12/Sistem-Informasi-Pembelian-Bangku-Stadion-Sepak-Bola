<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $guarded = [];

    public function seats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class, 'order_seat')
            ->withPivot('price')
            ->withTimestamps();
    }
}