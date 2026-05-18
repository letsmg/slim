<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use App\Services\VehicleService;
use App\Requests\StoreVehicleRequest;

/**
 * Testes para a entidade Vehicle
 * Cobre: Model, Repository, Service, Request (validação)
 */
class VehicleTest extends TestCase
{
    public function testModel()
    {
        $model = new Vehicle();
        $this->assertEquals("vehicles", $model->getTable());
    }

    public function testRepository()
    {
        $repo = new VehicleRepository();
        $this->assertInstanceOf(VehicleRepository::class, $repo);
    }

    public function testService()
    {
        $repo = new VehicleRepository();
        $service = new VehicleService($repo);
        $this->assertInstanceOf(VehicleService::class, $service);
    }

    public function testRequestValidation()
    {
        $request = new StoreVehicleRequest();
        $data = ["marca" => "", "modelo" => "", "crlv" => "", "tipo_combustivel" => ""];
        $this->expectException(\InvalidArgumentException::class);
        $request->validated($data);
    }

    public function testRequestValid()
    {
        $request = new StoreVehicleRequest();
        $data = [
            "marca" => "Fiat",
            "modelo" => "Toro",
            "crlv" => "123456789",
            "tipo_combustivel" => "Diesel",
            "eixos" => 2,
        ];
        $result = $request->validated($data);
        $this->assertIsArray($result);
        $this->assertEquals("Fiat", $result["marca"]);
    }
}
