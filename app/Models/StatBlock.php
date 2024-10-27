<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StatBlock extends Model
{
    protected function stats(): Attribute
    {
        return Attribute::make(
            get: function(string $value): array {
                $blocks = explode(',', $value);
                $stats = [];
                foreach($blocks as $block) {
                    $stat = explode(':', $block);
                    $stats[$stat[0]] = $stat[1];
                }
                return $stats;
            },
            set: function(array $values): string {
                $blocks = [];
                foreach($values as $statName => $value) {
                    $blocks[] = $statName . ':' . $value;
                }
                return implode(',', $blocks);
            }
        );
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class);
    }
}
