<?php

namespace App\Enums;

enum SportType: string
{
    case League = 'league';
    case Bowling = 'bowling';

    public function label(): string
    {
        return match ($this) {
            self::League => 'League',
            self::Bowling => 'Bowling',
        };
    }
}