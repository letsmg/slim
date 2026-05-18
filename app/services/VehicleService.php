<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use App\Requests\StoreVehicleRequest;

/**
 * Service de Vehicle - Regras de negócio para veículos
 * Segue ISO 27001: sanitização de entrada e validação
 * Valida unicidade de placa, chassi e renavam
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
     * Cria um novo veículo com dados sanitizados e valida unicidade
     */
    public function create(array $data): Vehicle
    {
        $data = sanitize_inputs($data);

        // Valida com StoreVehicleRequest (inclui unicidade de placa, chassi, renavam)
        $request = new StoreVehicleRequest();
        $data = $request->validated($data);

        return $this->vehicleRepository->create($data);
    }

    /**
     * Atualiza dados do veículo com sanitização e valida unicidade
     */
    public function update(Vehicle $vehicle, array $data): Vehicle
    {
        $data = sanitize_inputs($data);

        // Valida com StoreVehicleRequest ignorando o próprio ID
        $request = new StoreVehicleRequest($vehicle->id);
        $data = $request->validated($data);

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
