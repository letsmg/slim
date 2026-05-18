<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\ScheduledMaintenance;
use App\Repositories\ScheduledMaintenanceRepository;
use App\Services\ScheduledMaintenanceService;
use App\Requests\StoreScheduledMaintenanceRequest;

/**
 * Testes para a entidade ScheduledMaintenance
 * Cobre: Model, Repository, Service, Request (validação)
 */
class ScheduledMaintenanceTest extends TestCase
{
    public function testModel()
    {
        $model = new ScheduledMaintenance();
        $this->assertEquals("scheduled_maintenances", $model->getTable());
    }

    public function testRepository()
    {
        $repo = new ScheduledMaintenanceRepository();
        $this->assertInstanceOf(ScheduledMaintenanceRepository::class, $repo);
    }

    public function testService()
    {
        $repo = new ScheduledMaintenanceRepository();
        $service = new ScheduledMaintenanceService($repo);
        $this->assertInstanceOf(ScheduledMaintenanceService::class, $service);
    }

    public function testRequestValidation()
    {
        $request = new StoreScheduledMaintenanceRequest();
        $data = ["driver_id" => "", "vehicle_id" => "", "mechanic_id" => "", "scheduled_date" => "", "service" => ""];
        $this->expectException(\InvalidArgumentException::class);
        $request->validated($data);
    }

    public function testRequestValid()
    {
        $request = new StoreScheduledMaintenanceRequest();
        $data = [
            "driver_id" => 1,
            "vehicle_id" => 1,
            "mechanic_id" => 1,
            "scheduled_date" => "2026-05-25",
            "scheduled_time" => "08:00",
            "contact" => "(11) 98888-0001",
            "service" => "Troca de óleo e filtros",
            "observations" => "Utilizar óleo sintético 5W30",
            "completed" => false,
            "paid" => false,
        ];
        $result = $request->validated($data);
        $this->assertIsArray($result);
        $this->assertEquals("Troca de óleo e filtros", $result["service"]);
    }
}
