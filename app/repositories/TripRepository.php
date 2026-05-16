<?php

namespace App\Repositories;

use App\Models\Trip;

/**
 * Repository de Trip - Camada de acesso a dados de viagens
 */
class TripRepository
{
    public function create(array $data): Trip
    {
        return Trip::create($data);
    }

    public function findById(int $id): ?Trip
    {
        return Trip::with(['driver', 'vehicle'])->find($id);
    }

    public function findAll(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Trip::with(['driver', 'vehicle']);

        if (!empty($filters['driver_id'])) {
            $query->where('driver_id', $filters['driver_id']);
        }

        if (!empty($filters['vehicle_id'])) {
            $query->where('vehicle_id', $filters['vehicle_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('departure_forecast', 'desc')->get();
    }

    public function update(int $id, array $data): ?Trip
    {
        $trip = Trip::find($id);
        if ($trip === null) {
            return null;
        }
        $trip->fill($data);
        $trip->save();
        return $trip;
    }

    public function delete(int $id): bool
    {
        $trip = Trip::find($id);
        if ($trip === null) {
            return false;
        }
        return (bool) $trip->delete();
    }
}
