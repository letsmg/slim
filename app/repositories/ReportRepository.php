<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Product;

/**
 * Repository de Report - Agrega dados para relatórios
 * Centraliza consultas de estatísticas e métricas
 */
class ReportRepository
{
    /**
     * Retorna estatísticas gerais do sistema
     * 
     * @return array
     */
    public function getGeneralStats(): array
    {
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
    }

    /**
     * Retorna relatório detalhado de usuários
     * 
     * @return array
     */
    public function getUserReport(): array
    {
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
    }

    /**
     * Retorna relatório detalhado de produtos
     * 
     * @return array
     */
    public function getProductReport(): array
    {
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
    }
}