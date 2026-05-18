<?php

namespace App\Repositories;

use App\Models\Driver;

/**
 * Repository de Driver - Camada de acesso a dados de motoristas
 * Segue boas práticas: prepared statements via Eloquent ORM
 */
class DriverRepository
{
    /**
     * Cria um novo motorista (dados já sanitizados pelo Service)
     */
    public function create(array $data): Driver
    {
        return Driver::create($data);
    }

    /**
     * Busca motorista por ID
     */
    public function findById(int $id): ?Driver
    {
        return Driver::find($id);
    }

    /**
     * Lista todos os motoristas com filtros opcionais
     */
    public function findAll(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Driver::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['cpf'])) {
            $query->where('cpf', 'like', '%' . $filters['cpf'] . '%');
        }

        return $query->get();
    }

    /**
     * Atualiza dados do motorista
     */
    public function update(int $id, array $data): ?Driver
    {
        $driver = Driver::find($id);
        if ($driver === null) {
            return null;
        }
        $driver->fill($data);
        $driver->save();
        return $driver;
    }

    /**
     * Remove um motorista
     */
    public function delete(int $id): bool
    {
        $driver = Driver::find($id);
        if ($driver === null) {
            return false;
        }
        return (bool) $driver->delete();
    }
}
