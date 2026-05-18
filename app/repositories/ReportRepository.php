<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Trip;
use App\Models\ScheduledMaintenance;
use Illuminate\Database\QueryException;

/**
 * Repository de Report - Agrega dados para relatorios
 * Centraliza consultas de estatisticas e metricas da frota
 * Fallback para dados mockados quando o banco estiver indisponivel
 */
class ReportRepository
{
    /**
     * Tenta executar uma query, retorna fallback em caso de erro de conexao
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
     * Retorna estatisticas gerais do sistema de frotas
     * 
     * @return array
     */
    public function getGeneralStats(): array
    {
        return $this->safeQuery(function () {
            $currentMonth = date('Y-m');
            $tripsThisMonth = Trip::where('departure_forecast', 'like', $currentMonth . '%')->count();
            $maintenancesThisMonth = ScheduledMaintenance::where('scheduled_date', 'like', $currentMonth . '-%')->count();

            return [
                'users' => [
                    'total'   => User::count(),
                    'active'  => User::where('active', true)->count(),
                    'blocked' => User::where('active', false)->count(),
                ],
                'vehicles' => [
                    'total'  => Vehicle::count(),
                    'active' => Vehicle::count(), // todos os veiculos cadastrados sao ativos
                ],
                'trips' => [
                    'total'         => Trip::count(),
                    'this_month'    => $tripsThisMonth,
                    'pending'       => Trip::where('status', 'pending')->count(),
                    'in_progress'   => Trip::where('status', 'in_progress')->count(),
                    'completed'     => Trip::where('status', 'completed')->count(),
                ],
                'maintenances' => [
                    'total'         => ScheduledMaintenance::count(),
                    'this_month'    => $maintenancesThisMonth,
                    'pending'       => ScheduledMaintenance::where('completed', false)->count(),
                    'completed'     => ScheduledMaintenance::where('completed', true)->count(),
                ],
                'generated_at' => date('Y-m-d H:i:s'),
            ];
        }, [
            'users' => [
                'total'   => 0,
                'active'  => 0,
                'blocked' => 0,
            ],
            'vehicles' => [
                'total'  => 0,
                'active' => 0,
            ],
            'trips' => [
                'total'       => 0,
                'this_month'  => 0,
                'pending'     => 0,
                'in_progress' => 0,
                'completed'   => 0,
            ],
            'maintenances' => [
                'total'       => 0,
                'this_month'  => 0,
                'pending'     => 0,
                'completed'   => 0,
            ],
            'generated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Retorna relatorio detalhado de usuarios
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
                'total'    => User::count(),
                'active'   => User::where('active', true)->count(),
                'inactive' => User::where('active', false)->count(),
            ];

            return [
                'title'           => 'Relatorio de Usuarios',
                'total_by_status' => $totalByStatus,
                'recent_users'    => $recentUsers,
                'generated_at'    => date('Y-m-d H:i:s'),
            ];
        }, [
            'title'           => 'Relatorio de Usuarios',
            'total_by_status' => ['total' => 0, 'active' => 0, 'inactive' => 0],
            'recent_users'    => [],
            'generated_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Retorna relatorio detalhado de veiculos
     * 
     * @return array
     */
    public function getVehicleReport(): array
    {
        return $this->safeQuery(function () {
            $recentVehicles = Vehicle::orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->toArray();

            $stats = [
                'total'  => Vehicle::count(),
                'active' => Vehicle::count(),
            ];

            return [
                'title'           => 'Relatorio de Veiculos',
                'stats'           => $stats,
                'recent_vehicles' => $recentVehicles,
                'generated_at'    => date('Y-m-d H:i:s'),
            ];
        }, [
            'title'           => 'Relatorio de Veiculos',
            'stats'           => ['total' => 0, 'active' => 0],
            'recent_vehicles' => [],
            'generated_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Retorna relatorio detalhado de viagens
     * 
     * @return array
     */
    public function getTripReport(): array
    {
        return $this->safeQuery(function () {
            $recentTrips = Trip::orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->toArray();

            $stats = [
                'total'       => Trip::count(),
                'pending'     => Trip::where('status', 'pending')->count(),
                'in_progress' => Trip::where('status', 'in_progress')->count(),
                'completed'   => Trip::where('status', 'completed')->count(),
            ];

            return [
                'title'        => 'Relatorio de Viagens',
                'stats'        => $stats,
                'recent_trips' => $recentTrips,
                'generated_at' => date('Y-m-d H:i:s'),
            ];
        }, [
            'title'        => 'Relatorio de Viagens',
            'stats'        => ['total' => 0, 'pending' => 0, 'in_progress' => 0, 'completed' => 0],
            'recent_trips' => [],
            'generated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Retorna relatorio detalhado de manutencoes
     * 
     * @return array
     */
    public function getMaintenanceReport(): array
    {
        return $this->safeQuery(function () {
            $recentMaintenances = ScheduledMaintenance::orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->toArray();

            $stats = [
                'total'     => ScheduledMaintenance::count(),
                'pending'   => ScheduledMaintenance::where('completed', false)->count(),
                'completed' => ScheduledMaintenance::where('completed', true)->count(),
            ];

            return [
                'title'               => 'Relatorio de Manutencoes',
                'stats'               => $stats,
                'recent_maintenances' => $recentMaintenances,
                'generated_at'        => date('Y-m-d H:i:s'),
            ];
        }, [
            'title'               => 'Relatorio de Manutencoes',
            'stats'               => ['total' => 0, 'pending' => 0, 'completed' => 0],
            'recent_maintenances' => [],
            'generated_at'        => date('Y-m-d H:i:s'),
        ]);
    }
}
