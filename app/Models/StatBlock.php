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

    public function getStatModifiersAttribute(): array
    {
        $stats = $this->stats;
        $modifiers = [];

        $baseline = config('worldbench.modifier.baseline');
        $step = config('worldbench.modifier.step');

        foreach($stats as $name => $value) {
            $modifiers[$name] = floor(($value - $baseline) / $step);
        }
        return $modifiers;
    }

    public function getStatString(string $stat): string
    {
        $score = $this->stats[$stat];
        $modifier = $this->stat_modifiers[$stat];
        $prefix = $modifier < 0 ? '' : '+';
        return "{$score} ({$prefix}{$modifier})";
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class);
    }
}
