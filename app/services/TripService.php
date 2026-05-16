<?php

namespace App\Services;

use App\Models\Trip;
use App\Repositories\TripRepository;

/**
 * Service de Trip - Regras de negócio para viagens
 */
class TripService
{
    public function __construct(
        private TripRepository $tripRepository
    ) {}

    public function list(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        return $this->tripRepository->findAll($filters);
    }

    public function find(int $id): ?Trip
    {
        return $this->tripRepository->findById($id);
    }

    public function create(array $data): Trip
    {
        $data = sanitize_inputs($data);

        $required = ['driver_id', 'vehicle_id', 'departure_forecast', 'arrival_forecast'];
        $errors = [];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "O campo {$field} é obrigatório.";
            }
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode(['errors' => $errors]));
        }

        return $this->tripRepository->create($data);
    }

    public function update(Trip $trip, array $data): Trip
    {
        $data = sanitize_inputs($data);
        return $this->tripRepository->update($trip->id, $data);
    }

    public function delete(Trip $trip): bool
    {
        return $this->tripRepository->delete($trip->id);
    }
}
