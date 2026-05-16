<?php

namespace App\Enums;

/**
 * Níveis de acesso dos usuários (RBAC)
 * 
 * ADMIN: Acesso total e irrestrito a todas as rotas
 * OPERATIONAL: Apenas visualização (leitura) de motoristas, veículos e documentações
 * SUPPORT: Suporte técnico com permissões limitadas
 */
enum UserLevel: string
{
    case ADMIN = 'admin';
    case OPERATIONAL = 'operational';
    case SUPPORT = 'support';

    /**
     * Retorna o label amigável para exibição
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::OPERATIONAL => 'Operacional',
            self::SUPPORT => 'Suporte',
        };
    }

    /**
     * Retorna todos os valores válidos como array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
