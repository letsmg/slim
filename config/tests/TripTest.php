<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\Trip;
use App\Repositories\TripRepository;
use App\Services\TripService;
use App\Requests\StoreTripRequest;

/**
 * Testes para a entidade Trip
 * Cobre: Model, Repository, Service, Request (validação)
 */
class TripTest extends TestCase
{
    public function testModel()
    {
        $model = new Trip();
        $this->assertEquals("trips", $model->getTable());
    }

    public function testRepository()
    {
        $repo = new TripRepository();
        $this->assertInstanceOf(TripRepository::class, $repo);
    }

    public function testService()
    {
        $repo = new TripRepository();
        $service = new TripService($repo);
        $this->assertInstanceOf(TripService::class, $service);
    }

    public function testRequestValidation()
    {
        $request = new StoreTripRequest();
        $data = ["driver_id" => "", "vehicle_id" => "", "origin" => "", "destination" => "", "departure_forecast" => "", "status" => ""];
        $this->expectException(\InvalidArgumentException::class);
        $request->validated($data);
    }

    public function testRequestValid()
    {
        $request = new StoreTripRequest();
        $data = [
            "driver_id" => 1,
            "vehicle_id" => 1,
            "origin" => "São Paulo, SP",
            "destination" => "Rio de Janeiro, RJ",
            "departure_forecast" => "2026-05-19T08:00",
            "arrival_forecast" => "2026-05-21T18:00",
            "status" => "scheduled",
        ];
        $result = $request->validated($data);
        $this->assertIsArray($result);
        $this->assertEquals("São Paulo, SP", $result["origin"]);
    }
}
