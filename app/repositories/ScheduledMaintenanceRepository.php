<?php

namespace App\Repositories;

use App\Models\ScheduledMaintenance;

/**
 * Repository de ScheduledMaintenance - Camada de acesso a dados de manutenções programadas
 */
class ScheduledMaintenanceRepository
{
    public function create(array $data): ScheduledMaintenance
    {
        return ScheduledMaintenance::create($data);
    }

    public function findById(int $id): ?ScheduledMaintenance
    {
        return ScheduledMaintenance::with(['driver', 'vehicle', 'mechanic'])->find($id);
    }

    public function findAll(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = ScheduledMaintenance::with(['driver', 'vehicle', 'mechanic']);

        if (!empty($filters['driver_id'])) {
            $query->where('driver_id', $filters['driver_id']);
        }

        if (!empty($filters['vehicle_id'])) {
            $query->where('vehicle_id', $filters['vehicle_id']);
        }

        if (!empty($filters['mechanic_id'])) {
            $query->where('mechanic_id', $filters['mechanic_id']);
        }

        if (isset($filters['completed'])) {
            $query->where('completed', filter_var($filters['completed'], FILTER_VALIDATE_BOOLEAN));
        }

        return $query->orderBy('scheduled_date', 'desc')->get();
    }

    public function update(int $id, array $data): ?ScheduledMaintenance
    {
        $maintenance = ScheduledMaintenance::find($id);
        if ($maintenance === null) {
            return null;
        }
        $maintenance->fill($data);
        $maintenance->save();
        return $maintenance;
    }

    public function delete(int $id): bool
    {
        $maintenance = ScheduledMaintenance::find($id);
        if ($maintenance === null) {
            return false;
        }
        return (bool) $maintenance->delete();
    }
}
