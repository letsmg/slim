<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Repositories\VehicleRepository;

/**
 * Service de Vehicle - Regras de negócio para veículos
 * Segue ISO 27001: sanitização de entrada e validação
 */
class VehicleService
{
    public function __construct(
        private VehicleRepository $vehicleRepository
    ) {}

    /**
     * Lista veículos com filtros opcionais
     */
    public function list(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        return $this->vehicleRepository->findAll($filters);
    }

    /**
     * Busca veículo por ID
     */
    public function find(int $id): ?Vehicle
    {
        return $this->vehicleRepository->findById($id);
    }

    /**
     * Cria um novo veículo com dados sanitizados
     */
    public function create(array $data): Vehicle
    {
        $data = sanitize_inputs($data);

        // Valida campos obrigatórios
        $required = ['marca', 'modelo', 'crlv', 'tipo_combustivel'];
        $errors = [];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "O campo {$field} é obrigatório.";
            }
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode(['errors' => $errors]));
        }

        return $this->vehicleRepository->create($data);
    }

    /**
     * Atualiza dados do veículo com sanitização
     */
    public function update(Vehicle $vehicle, array $data): Vehicle
    {
        $data = sanitize_inputs($data);

        return $this->vehicleRepository->update($vehicle->id, $data);
    }

    /**
     * Remove um veículo
     */
    public function delete(Vehicle $vehicle): bool
    {
        return $this->vehicleRepository->delete($vehicle->id);
    }
}
