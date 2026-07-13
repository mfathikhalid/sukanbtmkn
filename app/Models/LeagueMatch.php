<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\MatchStage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LeagueMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'sport_id',
        'gender',
        'stage',
        'match_no',
        'home_house_id',
        'away_house_id',
        'played_at',
    ];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'stage' => MatchStage::class,
            'played_at' => 'datetime',
        ];
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function homeHouse(): BelongsTo
    {
        return $this->belongsTo(House::class, 'home_house_id');
    }

    public function awayHouse(): BelongsTo
    {
        return $this->belongsTo(House::class, 'away_house_id');
    }

    public function result(): HasOne
    {
        return $this->hasOne(MatchResult::class, 'match_id');
    }
}
