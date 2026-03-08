<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statistic extends Model
{
    protected $fillable = [
        'user_id',
        'sport_type',
        'game_date',
        'points',
        'assists',
        'rebounds',
        'steals',
        'blocks',
        'goals',
        'assists_football',
        'shots',
        'passes',
        'tackles',
        'spikes',
        'blocks_volleyball',
        'serves',
        'digs',
        'aces',
        'minutes_played',
        'notes'
    ];

    protected $casts = [
        'game_date' => 'date',
        'points' => 'integer',
        'assists' => 'integer',
        'rebounds' => 'integer',
        'steals' => 'integer',
        'blocks' => 'integer',
        'goals' => 'integer',
        'assists_football' => 'integer',
        'shots' => 'integer',
        'passes' => 'integer',
        'tackles' => 'integer',
        'spikes' => 'integer',
        'blocks_volleyball' => 'integer',
        'serves' => 'integer',
        'digs' => 'integer',
        'aces' => 'integer',
        'minutes_played' => 'integer',
    ];

    /**
     * Get the user that owns the statistic
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get relevant stats based on sport type
     */
    public function getRelevantStatsAttribute(): array
    {
        return match($this->sport_type) {
            'Basketball' => [
                'Points' => $this->points,
                'Assists' => $this->assists,
                'Rebounds' => $this->rebounds,
                'Steals' => $this->steals,
                'Blocks' => $this->blocks,
            ],
            'Football' => [
                'Goals' => $this->goals,
                'Assists' => $this->assists_football,
                'Shots' => $this->shots,
                'Passes' => $this->passes,
                'Tackles' => $this->tackles,
            ],
            'Volleyball' => [
                'Spikes' => $this->spikes,
                'Blocks' => $this->blocks_volleyball,
                'Serves' => $this->serves,
                'Digs' => $this->digs,
                'Aces' => $this->aces,
            ],
            default => [],
        };
    }

    /**
     * Scope to filter by sport type
     */
    public function scopeSportType($query, $sportType)
    {
        return $query->where('sport_type', $sportType);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('game_date', [$startDate, $endDate]);
    }

    /**
     * Get sport icon
     */
    public function getSportIconAttribute(): string
    {
        return match($this->sport_type) {
            'Basketball' => 'mdi-basketball',
            'Football' => 'mdi-soccer',
            'Volleyball' => 'mdi-volleyball',
            default => 'mdi-trophy',
        };
    }
}
