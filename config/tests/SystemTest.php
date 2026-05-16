<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Mechanic;
use App\Models\Trip;
use App\Models\ScheduledMaintenance;
use App\Repositories\UserRepository;
use App\Repositories\VehicleRepository;
use App\Repositories\DriverRepository;
use App\Repositories\MechanicRepository;
use App\Repositories\TripRepository;
use App\Repositories\ScheduledMaintenanceRepository;
use App\Services\UserService;
use App\Services\VehicleService;
use App\Services\DriverService;
use App\Services\MechanicService;
use App\Services\TripService;
use App\Services\ScheduledMaintenanceService;
use App\Requests\StoreUserRequest;
use App\Requests\StoreVehicleRequest;
use App\Requests\StoreDriverRequest;

/**
 * Testes de sistema para todas as entidades do Gerenciador de Frotas
 * Cobre: Models, Repositories, Services, Requests (validação)
 * Segue boas práticas ISO 27001: validação de dados e sanitização
 */
class SystemTest extends TestCase
{
    // ==================== USERS ====================

    public function testUserModel()
    {
        $model = new User();
        $this->assertEquals("users", $model->getTable());
    }

    public function testUserRepository()
    {
        $repo = new UserRepository();
        $this->assertInstanceOf(UserRepository::class, $repo);
    }

    public function testUserService()
    {
        $repo = new UserRepository();
        $service = new UserService($repo);
        $this->assertInstanceOf(UserService::class, $service);
    }

    public function testStoreUserRequestValidation()
    {
        $request = new StoreUserRequest();
        $data = ["name" => "", "email" => "invalido", "password" => "123", "active" => "invalido"];
        $this->expectException(\InvalidArgumentException::class);
        $request->validated($data);
    }

    public function testStoreUserRequestValid()
    {
        $request = new StoreUserRequest();
        $data = ["name" => "Teste", "email" => "teste@teste.com", "password" => "Str0ng!Pass", "active" => true];
        $result = $request->validated($data);
        $this->assertIsArray($result);
        $this->assertEquals("Teste", $result["name"]);
    }

    // ==================== VEHICLES ====================

    public function testVehicleModel()
    {
        $model = new Vehicle();
        $this->assertEquals("vehicles", $model->getTable());
    }

    public function testVehicleRepository()
    {
        $repo = new VehicleRepository();
        $this->assertInstanceOf(VehicleRepository::class, $repo);
    }

    public function testVehicleService()
    {
        $repo = new VehicleRepository();
        $service = new VehicleService($repo);
        $this->assertInstanceOf(VehicleService::class, $service);
    }

    public function testStoreVehicleRequestValidation()
    {
        $request = new StoreVehicleRequest();
        $data = ["marca" => "", "modelo" => "", "crlv" => "", "tipo_combustivel" => ""];
        $this->expectException(\InvalidArgumentException::class);
        $request->validated($data);
    }

    public function testStoreVehicleRequestValid()
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

    // ==================== DRIVERS ====================

    public function testDriverModel()
    {
        $model = new Driver();
        $this->assertEquals("drivers", $model->getTable());
    }

    public function testDriverRepository()
    {
        $repo = new DriverRepository();
        $this->assertInstanceOf(DriverRepository::class, $repo);
    }

    public function testDriverService()
    {
        $repo = new DriverRepository();
        $service = new DriverService($repo);
        $this->assertInstanceOf(DriverService::class, $service);
    }

    public function testStoreDriverRequestValidation()
    {
        $request = new StoreDriverRequest();
        $data = ["nome" => "", "cpf" => "", "cnh" => "", "categoria_cnh" => ""];
        $this->expectException(\InvalidArgumentException::class);
        $request->validated($data);
    }

    public function testStoreDriverRequestValid()
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

    // ==================== MECHANICS ====================

    public function testMechanicModel()
    {
        $model = new Mechanic();
        $this->assertEquals("mechanics", $model->getTable());
    }

    public function testMechanicRepository()
    {
        $repo = new MechanicRepository();
        $this->assertInstanceOf(MechanicRepository::class, $repo);
    }

    public function testMechanicService()
    {
        $repo = new MechanicRepository();
        $service = new MechanicService($repo);
        $this->assertInstanceOf(MechanicService::class, $service);
    }

    // ==================== TRIPS ====================

    public function testTripModel()
    {
        $model = new Trip();
        $this->assertEquals("trips", $model->getTable());
    }

    public function testTripRepository()
    {
        $repo = new TripRepository();
        $this->assertInstanceOf(TripRepository::class, $repo);
    }

    public function testTripService()
    {
        $repo = new TripRepository();
        $service = new TripService($repo);
        $this->assertInstanceOf(TripService::class, $service);
    }

    // ==================== SCHEDULED MAINTENANCES ====================

    public function testScheduledMaintenanceModel()
    {
        $model = new ScheduledMaintenance();
        $this->assertEquals("scheduled_maintenances", $model->getTable());
    }

    public function testScheduledMaintenanceRepository()
    {
        $repo = new ScheduledMaintenanceRepository();
        $this->assertInstanceOf(ScheduledMaintenanceRepository::class, $repo);
    }

    public function testScheduledMaintenanceService()
    {
        $repo = new ScheduledMaintenanceRepository();
        $service = new ScheduledMaintenanceService($repo);
        $this->assertInstanceOf(ScheduledMaintenanceService::class, $service);
    }

    // ==================== SANITIZAÇÃO (ISO 27001) ====================

    public function testSanitizeInputsHelper()
    {
        // Testa se a função sanitize_inputs existe e funciona
        $this->assertTrue(function_exists('sanitize_inputs'));

        $dirty = [
            "name" => "  <script>alert('xss')</script>João  ",
            "email" => "  TESTE@EXEMPLO.COM  ",
        ];

        $clean = sanitize_inputs($dirty);

        // Verifica trim
        $this->assertStringStartsNotWith(" ", $clean["name"]);
        $this->assertStringEndsNotWith(" ", $clean["name"]);

        // Verifica strip_tags
        $this->assertStringNotContainsString("<script>", $clean["name"]);
    }

    public function testPasswordHashArgon2id()
    {
        // Verifica que o hash usa Argon2id
        $password = "Str0ng!Pass";
        $hash = password_hash($password, PASSWORD_ARGON2ID);

        $this->assertStringContainsString('argon2id', $hash);
        $this->assertTrue(password_verify($password, $hash));
    }
}
