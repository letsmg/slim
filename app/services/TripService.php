<?php

namespace App\Services;

use App\Models\Trip;
use App\Repositories\TripRepository;
use App\Requests\StoreTripRequest;

/**
 * Service de Trip - Regras de negócio para viagens
 * Inclui validação de jornada de trabalho do motorista
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

    /**
     * Cria uma nova viagem com validação de jornada de trabalho
     */
    public function create(array $data): Trip
    {
        $data = sanitize_inputs($data);

        // Valida com StoreTripRequest (inclui regras de jornada)
        $request = new StoreTripRequest();
        $data = $request->validated($data);

        return $this->tripRepository->create($data);
    }

    /**
     * Atualiza uma viagem com validação de jornada de trabalho
     */
    public function update(Trip $trip, array $data): Trip
    {
        $data = sanitize_inputs($data);

        // Valida com StoreTripRequest ignorando o próprio ID
        $request = new StoreTripRequest($trip->id);
        $data = $request->validated($data);

        return $this->tripRepository->update($trip->id, $data);
    }

    public function delete(Trip $trip): bool
    {
        return $this->tripRepository->delete($trip->id);
    }
}
