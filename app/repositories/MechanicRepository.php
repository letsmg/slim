<?php

namespace App\Repositories;

use App\Models\Mechanic;

/**
 * Repository de Mechanic - Camada de acesso a dados de mecânicos
 */
class MechanicRepository
{
    public function create(array $data): Mechanic
    {
        return Mechanic::create($data);
    }

    public function findById(int $id): ?Mechanic
    {
        return Mechanic::find($id);
    }

    public function findAll(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Mechanic::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['document'])) {
            $query->where('document', 'like', '%' . $filters['document'] . '%');
        }

        return $query->get();
    }

    public function update(int $id, array $data): ?Mechanic
    {
        $mechanic = Mechanic::find($id);
        if ($mechanic === null) {
            return null;
        }
        $mechanic->fill($data);
        $mechanic->save();
        return $mechanic;
    }

    public function delete(int $id): bool
    {
        $mechanic = Mechanic::find($id);
        if ($mechanic === null) {
            return false;
        }
        return (bool) $mechanic->delete();
    }
}
