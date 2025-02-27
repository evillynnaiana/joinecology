<?php

namespace App\Enums;

class AreaStatus
{
    const PENDING = 0;
    const UNDER_REVIEW = 1;
    const REJECTED = 2;
    const ACCEPTED = 3;

    public static function getStatus(int $status): string
    {
        return match ($status) {
            self::PENDING => 'Pendente',
            self::UNDER_REVIEW => 'Em Análise',
            self::REJECTED => 'Reprovada',
            self::ACCEPTED => 'Aprovada',
            default => 'Desconhecido',
        };
    }
}
