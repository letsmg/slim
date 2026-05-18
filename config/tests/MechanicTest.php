<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\Mechanic;
use App\Repositories\MechanicRepository;
use App\Services\MechanicService;
use App\Requests\StoreMechanicRequest;

/**
 * Testes para a entidade Mechanic
 * Cobre: Model, Repository, Service, Request (validação)
 */
class MechanicTest extends TestCase
{
    public function testModel()
    {
        $model = new Mechanic();
        $this->assertEquals("mechanics", $model->getTable());
    }

    public function testRepository()
    {
        $repo = new MechanicRepository();
        $this->assertInstanceOf(MechanicRepository::class, $repo);
    }

    public function testService()
    {
        $repo = new MechanicRepository();
        $service = new MechanicService($repo);
        $this->assertInstanceOf(MechanicService::class, $service);
    }

    public function testRequestValidation()
    {
        $request = new StoreMechanicRequest();
        $data = ["name" => "", "document" => "", "phone1" => ""];
        $this->expectException(\InvalidArgumentException::class);
        $request->validated($data);
    }

    public function testRequestValid()
    {
        $request = new StoreMechanicRequest();
        $data = [
            "name" => "Oficina do João",
            "address" => "Rua das Oficinas, 100",
            "document" => "11.222.333/0001-01",
            "phone1" => "(11) 98888-0001",
            "phone2" => "(11) 97777-0001",
        ];
        $result = $request->validated($data);
        $this->assertIsArray($result);
        $this->assertEquals("Oficina do João", $result["name"]);
    }
}
