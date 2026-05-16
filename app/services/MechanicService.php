<?php

namespace App\Services;

use App\Models\Mechanic;
use App\Repositories\MechanicRepository;

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

        $required = ['name', 'document', 'phone1'];
        $errors = [];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "O campo {$field} é obrigatório.";
            }
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode(['errors' => $errors]));
        }

        return $this->mechanicRepository->create($data);
    }

    public function update(Mechanic $mechanic, array $data): Mechanic
    {
        $data = sanitize_inputs($data);
        return $this->mechanicRepository->update($mechanic->id, $data);
    }

    public function delete(Mechanic $mechanic): bool
    {
        return $this->mechanicRepository->delete($mechanic->id);
    }
}
