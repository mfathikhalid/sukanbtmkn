<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'home_score',
        'away_score',
        'winner_house_id',
    ];

    public function leagueMatch(): BelongsTo
    {
        return $this->belongsTo(LeagueMatch::class, 'match_id');
    }

    public function winnerHouse(): BelongsTo
    {
        return $this->belongsTo(House::class, 'winner_house_id');
    }
}
