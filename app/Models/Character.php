<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function statBlock(): BelongsTo
    {
        return $this->belongsTo(StatBlock::class);
    }
}
