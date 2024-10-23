<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Location extends Model
{
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function maps():HasMany
    {
        return $this->hasMany(Map::class);
    }

    protected function map(): Attribute
    {
        return Attribute::make(
            get: fn(): string => $this->maps->pluck('image')->first() ?? ''
        );
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }
}
