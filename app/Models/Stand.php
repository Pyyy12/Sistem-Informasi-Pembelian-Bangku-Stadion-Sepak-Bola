<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stand extends Model
{
    protected $guarded = [];

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}