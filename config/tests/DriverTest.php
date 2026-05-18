<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\Driver;
use App\Repositories\DriverRepository;
use App\Services\DriverService;
use App\Requests\StoreDriverRequest;

/**
 * Testes para a entidade Driver
 * Cobre: Model, Repository, Service, Request (validação)
 */
class DriverTest extends TestCase
{
    public function testModel()
    {
        $model = new Driver();
        $this->assertEquals("drivers", $model->getTable());
    }

    public function testRepository()
    {
        $repo = new DriverRepository();
        $this->assertInstanceOf(DriverRepository::class, $repo);
    }

    public function testService()
    {
        $repo = new DriverRepository();
        $service = new DriverService($repo);
        $this->assertInstanceOf(DriverService::class, $service);
    }

    public function testRequestValidation()
    {
        $request = new StoreDriverRequest();
        $data = ["nome" => "", "cpf" => "", "cnh" => "", "categoria_cnh" => ""];
        $this->expectException(\InvalidArgumentException::class);
        $request->validated($data);
    }

    public function testRequestValid()
    {
        $request = new StoreDriverRequest();
        $data = [
            "nome" => "Pedro Alves",
            "cpf" => "123.456.789-00",
            "cnh" => "12345678901",
            "categoria_cnh" => "D",
            "rg" => "12.345.678-9",
            "endereco" => "Rua Exemplo, 123",
            "cidade" => "São Paulo",
            "estado" => "SP",
        ];
        $result = $request->validated($data);
        $this->assertIsArray($result);
        $this->assertEquals("Pedro Alves", $result["nome"]);
    }
}
