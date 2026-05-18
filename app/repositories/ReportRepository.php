<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\QueryException;

/**
 * Repository de Report - Agrega dados para relatórios
 * Centraliza consultas de estatísticas e métricas
 * Fallback para dados mockados quando o banco estiver indisponível
 */
class ReportRepository
{
    /**
     * Tenta executar uma query, retorna fallback em caso de erro de conexão
     */
    private function safeQuery(callable $callback, $fallback)
    {
        try {
            return $callback();
        } catch (QueryException $e) {
            return $fallback;
        }
    }

    /**
     * Retorna estatísticas gerais do sistema
     * 
     * @return array
     */
    public function getGeneralStats(): array
    {
        return $this->safeQuery(function () {
            return [
                'users' => [
                    'total'   => User::count(),
                    'active'  => User::where('active', true)->count(),
                    'blocked' => User::where('active', false)->count(),
                ],
                'products' => [
                    'total'       => Product::count(),
                    'active'      => Product::where('active', true)->count(),
                    'inactive'    => Product::where('active', false)->count(),
                    'total_stock' => Product::sum('stock'),
                    'avg_price'   => round((float) Product::avg('price'), 2),
                ],
                'generated_at' => date('Y-m-d H:i:s'),
            ];
        }, [
            'users' => [
                'total'   => 0,
                'active'  => 0,
                'blocked' => 0,
            ],
            'products' => [
                'total'       => 0,
                'active'      => 0,
                'inactive'    => 0,
                'total_stock' => 0,
                'avg_price'   => 0,
            ],
            'generated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Retorna relatório detalhado de usuários
     * 
     * @return array
     */
    public function getUserReport(): array
    {
        return $this->safeQuery(function () {
            $recentUsers = User::orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->toArray();

            $totalByStatus = [
                'total'  => User::count(),
                'active' => User::where('active', true)->count(),
                'inactive' => User::where('active', false)->count(),
            ];

            return [
                'title'      => 'Relatório de Usuários',
                'total_by_status' => $totalByStatus,
                'recent_users'    => $recentUsers,
                'generated_at'    => date('Y-m-d H:i:s'),
            ];
        }, [
            'title'      => 'Relatório de Usuários',
            'total_by_status' => ['total' => 0, 'active' => 0, 'inactive' => 0],
            'recent_users'    => [],
            'generated_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Retorna relatório detalhado de produtos
     * 
     * @return array
     */
    public function getProductReport(): array
    {
        return $this->safeQuery(function () {
            $recentProducts = Product::orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->toArray();

            $stats = [
                'total'       => Product::count(),
                'active'      => Product::where('active', true)->count(),
                'inactive'    => Product::where('active', false)->count(),
                'total_stock' => Product::sum('stock'),
                'avg_price'   => round((float) Product::avg('price'), 2),
            ];

            return [
                'title'    => 'Relatório de Produtos',
                'stats'    => $stats,
                'recent_products' => $recentProducts,
                'generated_at'    => date('Y-m-d H:i:s'),
            ];
        }, [
            'title'    => 'Relatório de Produtos',
            'stats'    => ['total' => 0, 'active' => 0, 'inactive' => 0, 'total_stock' => 0, 'avg_price' => 0],
            'recent_products' => [],
            'generated_at'    => date('Y-m-d H:i:s'),
        ]);
    }
}
