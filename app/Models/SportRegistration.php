<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SportRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'sport_id',
        'participant_id',
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