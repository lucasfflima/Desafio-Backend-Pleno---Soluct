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

    public static function labels(): array
    {
        return [
            self::PENDING->value => 'Pendente',
            self::IN_PROGRESS->value => 'Em Andamento',
            self::COMPLETED->value => 'Concluída',
            self::CANCELED->value => 'Cancelada',
        ];
    }
}
