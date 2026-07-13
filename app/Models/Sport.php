<?php

namespace App\Models;

use App\Enums\SportType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'gender_based',
        'male_quota',
        'female_quota',
    ];

    protected function casts(): array
    {
        return [
            'type' => SportType::class,
            'gender_based' => 'boolean',
        ];
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class, 'sport_registrations')->withTimestamps();
    }

    public function matches(): HasMany
    {
        return $this->hasMany(LeagueMatch::class);
    }

    public function bowlingScores(): HasMany
    {
        return $this->hasMany(BowlingScore::class);
    }
}