<?php

namespace App\Services;

use App\Models\Driver;
use App\Repositories\DriverRepository;

/**
 * Service de Driver - Regras de negócio para motoristas
 * Segue ISO 27001: sanitização de entrada e validação
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
     * Cria um novo motorista com dados sanitizados
     */
    public function create(array $data): Driver
    {
        $data = sanitize_inputs($data);

        // Valida campos obrigatórios
        $required = ['nome', 'cpf', 'cnh', 'categoria_cnh'];
        $errors = [];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "O campo {$field} é obrigatório.";
            }
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode(['errors' => $errors]));
        }

        return $this->driverRepository->create($data);
    }

    /**
     * Atualiza dados do motorista com sanitização
     */
    public function update(Driver $driver, array $data): Driver
    {
        $data = sanitize_inputs($data);

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
