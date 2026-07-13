<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BowlingScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'sport_id',
        'participant_id',
        'game_number',
        'score',
    ];

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }
}
