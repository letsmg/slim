<?php

namespace App\Services;

use App\Repositories\ReportRepository;

/**
 * Service de Report - Camada de lógica para relatórios
 * Orquestra a geração de relatórios a partir dos repositórios
 */
class ReportService
{
    public function __construct(
        private ReportRepository $repository
    ) {}

    /**
     * Lista todos os tipos de relatório disponíveis
     * 
     * @return array
     */
    public function list(): array
    {
        return [
            [
                'id'          => 'general',
                'name'        => 'Relatório Geral',
                'description' => 'Estatísticas gerais do sistema (usuários e produtos)',
            ],
            [
                'id'          => 'users',
                'name'        => 'Relatório de Usuários',
                'description' => 'Detalhamento de usuários cadastrados e status',
            ],
            [
                'id'          => 'products',
                'name'        => 'Relatório de Produtos',
                'description' => 'Detalhamento de produtos, estoque e preços',
            ],
        ];
    }

    /**
     * Gera um relatório baseado no tipo
     * 
     * @param string $type Tipo do relatório (general, users, products)
     * @return array|null
     */
    public function generate(string $type): ?array
    {
        return match ($type) {
            'general'  => $this->repository->getGeneralStats(),
            'users'    => $this->repository->getUserReport(),
            'products' => $this->repository->getProductReport(),
            default    => null,
        };
    }
}