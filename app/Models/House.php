<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(LeagueMatch::class, 'home_house_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(LeagueMatch::class, 'away_house_id');
    }
}