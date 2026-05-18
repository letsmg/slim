<?php

namespace App\Services;

use App\Models\Driver;
use App\Repositories\DriverRepository;
use App\Requests\StoreDriverRequest;

/**
 * Service de Driver - Regras de negócio para motoristas
 * Segue ISO 27001: sanitização de entrada e validação
 * Valida unicidade de CPF
 */
class DriverService
{
    public function __construct(
        private DriverRepository $driverRepository
    ) {}

    /**
     * Lista motoristas com filtros opcionais
     */
    public function list(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        return $this->driverRepository->findAll($filters);
    }

    /**
     * Busca motorista por ID
     */
    public function find(int $id): ?Driver
    {
        return $this->driverRepository->findById($id);
    }

    /**
     * Cria um novo motorista com dados sanitizados e valida CPF único
     */
    public function create(array $data): Driver
    {
        $data = sanitize_inputs($data);

        // Valida com StoreDriverRequest (inclui unicidade de CPF)
        $request = new StoreDriverRequest();
        $data = $request->validated($data);

        return $this->driverRepository->create($data);
    }

    /**
     * Atualiza dados do motorista com sanitização e valida CPF único
     */
    public function update(Driver $driver, array $data): Driver
    {
        $data = sanitize_inputs($data);

        // Valida com StoreDriverRequest ignorando o próprio ID
        $request = new StoreDriverRequest($driver->id);
        $data = $request->validated($data);

        return $this->driverRepository->update($driver->id, $data);
    }

    /**
     * Remove um motorista
     */
    public function delete(Driver $driver): bool
    {
        return $this->driverRepository->delete($driver->id);
    }
}
