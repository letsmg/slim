<?php

namespace App\Services;

use App\Models\Mechanic;
use App\Repositories\MechanicRepository;
use App\Requests\StoreMechanicRequest;

/**
 * Service de Mechanic - Regras de negócio para mecânicos
 */
class MechanicService
{
    public function __construct(
        private MechanicRepository $mechanicRepository
    ) {}

    public function list(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        return $this->mechanicRepository->findAll($filters);
    }

    public function find(int $id): ?Mechanic
    {
        return $this->mechanicRepository->findById($id);
    }

    public function create(array $data): Mechanic
    {
        $data = sanitize_inputs($data);

        // Valida com StoreMechanicRequest
        $request = new StoreMechanicRequest();
        $data = $request->validated($data);

        return $this->mechanicRepository->create($data);
    }

    public function update(Mechanic $mechanic, array $data): Mechanic
    {
        $data = sanitize_inputs($data);

        // Valida com StoreMechanicRequest
        $request = new StoreMechanicRequest();
        $data = $request->validated($data);

        return $this->mechanicRepository->update($mechanic->id, $data);
    }

    public function delete(Mechanic $mechanic): bool
    {
        return $this->mechanicRepository->delete($mechanic->id);
    }
}
