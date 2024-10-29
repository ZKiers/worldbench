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
        $modifiers = [];

        $baseline = config('worldbench.modifier.baseline');
        $step = config('worldbench.modifier.step');

        foreach($this->stats as $name => $value) {
            $modifiers[$name] = floor(($value - $baseline) / $step);
        }
        return $modifiers;
    }

    protected function savingThrows(): Attribute
    {
        return Attribute::make(
            get: function(?string $value = ''): array|null {
                $savingThrows = explode(',', $value);
                return is_array($savingThrows) ? $savingThrows : null;
            },
            set: function(array $savingThrows): string {
                return implode(',', $savingThrows);
            }
        );
    }

    public function getSavingThrowModifiersAttribute(): array
    {
        $savingThrowModifiers = [];
        foreach($this->statModifiers as $name => $modifier) {
            $savingThrowModifiers[$name] = $this->addScorePrefix(
                in_array($name, $this->saving_throws) ? $modifier + $this->proficiency : $modifier
            );
        }
        return $savingThrowModifiers;
    }

    public function getStatString(string $stat): string
    {
        $score = $this->stats[$stat];
        $modifier = $this->stat_modifiers[$stat];
        return "{$score} ({$this->addScorePrefix($modifier)})";
    }

    public function addScorePrefix(int $score): string
    {
        return $score < 0 ? $score : "+{$score}";
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class);
    }
}
