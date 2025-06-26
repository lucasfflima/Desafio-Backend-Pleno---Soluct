<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pendente';
    case IN_PROGRESS = 'em_andamento';
    case COMPLETED = 'concluida';
    case CANCELED = 'cancelada';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
