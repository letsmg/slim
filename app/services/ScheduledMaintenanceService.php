<?php

namespace App\Services;

use App\Models\ScheduledMaintenance;
use App\Repositories\ScheduledMaintenanceRepository;
use App\Requests\StoreScheduledMaintenanceRequest;

/**
 * Service de ScheduledMaintenance - Regras de negócio para manutenções programadas
 */
class ScheduledMaintenanceService
{
    public function __construct(
        private ScheduledMaintenanceRepository $scheduledMaintenanceRepository
    ) {}

    public function list(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        return $this->scheduledMaintenanceRepository->findAll($filters);
    }

    public function find(int $id): ?ScheduledMaintenance
    {
        return $this->scheduledMaintenanceRepository->findById($id);
    }

    public function create(array $data): ScheduledMaintenance
    {
        $data = sanitize_inputs($data);

        // Valida com StoreScheduledMaintenanceRequest
        $request = new StoreScheduledMaintenanceRequest();
        $data = $request->validated($data);

        return $this->scheduledMaintenanceRepository->create($data);
    }

    public function update(ScheduledMaintenance $maintenance, array $data): ScheduledMaintenance
    {
        $data = sanitize_inputs($data);

        // Valida com StoreScheduledMaintenanceRequest
        $request = new StoreScheduledMaintenanceRequest();
        $data = $request->validated($data);

        return $this->scheduledMaintenanceRepository->update($maintenance->id, $data);
    }

    public function delete(ScheduledMaintenance $maintenance): bool
    {
        return $this->scheduledMaintenanceRepository->delete($maintenance->id);
    }
}
