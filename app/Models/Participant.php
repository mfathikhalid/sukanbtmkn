<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_id',
        'employee_no',
        'name',
        'gender',
        'department',
    ];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
        ];
    }

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function sports(): BelongsToMany
    {
        return $this->belongsToMany(Sport::class, 'sport_registrations')->withTimestamps();
    }

    public function bowlingScores(): HasMany
    {
        return $this->hasMany(BowlingScore::class);
    }
}