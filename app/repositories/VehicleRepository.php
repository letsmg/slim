<?php

namespace App\Repositories;

use App\Models\Vehicle;

/**
 * Repository de Vehicle - Camada de acesso a dados de veículos
 * Segue boas práticas: prepared statements via Eloquent ORM
 */
class VehicleRepository
{
    /**
     * Cria um novo veículo (dados já sanitizados pelo Service)
     */
    public function create(array $data): Vehicle
    {
        return Vehicle::create($data);
    }

    /**
     * Busca veículo por ID
     */
    public function findById(int $id): ?Vehicle
    {
        return Vehicle::find($id);
    }

    /**
     * Lista todos os veículos com filtros opcionais
     */
    public function findAll(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Vehicle::query();

        if (!empty($filters['brand'])) {
            $query->where('brand', 'like', '%' . $filters['brand'] . '%');
        }

        if (!empty($filters['model'])) {
            $query->where('model', 'like', '%' . $filters['model'] . '%');
        }

        return $query->get();
    }

    /**
     * Atualiza dados do veículo
     */
    public function update(int $id, array $data): ?Vehicle
    {
        $vehicle = Vehicle::find($id);
        if ($vehicle === null) {
            return null;
        }
        $vehicle->fill($data);
        $vehicle->save();
        return $vehicle;
    }

    /**
     * Remove um veículo
     */
    public function delete(int $id): bool
    {
        $vehicle = Vehicle::find($id);
        if ($vehicle === null) {
            return false;
        }
        return (bool) $vehicle->delete();
    }
}
