<?php

namespace App\Enums;

enum MatchStage: string
{
    case League = 'league';
    case SemiFinal = 'semi_final';
    case ThirdPlace = 'third_place';
    case Final = 'final';

    public function label(): string
    {
        return match ($this) {
            self::League => 'Liga',
            self::SemiFinal => 'Separuh Akhir',
            self::ThirdPlace => 'Tempat Ketiga',
            self::Final => 'Final',
        };
    }
}
