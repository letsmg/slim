<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Requests\StoreUserRequest;

/**
 * Testes para a entidade User
 * Cobre: Model, Repository, Service, Request (validação)
 */
class UserTest extends TestCase
{
    public function testModel()
    {
        $model = new User();
        $this->assertEquals("users", $model->getTable());
    }

    public function testRepository()
    {
        $repo = new UserRepository();
        $this->assertInstanceOf(UserRepository::class, $repo);
    }

    public function testService()
    {
        $repo = new UserRepository();
        $service = new UserService($repo);
        $this->assertInstanceOf(UserService::class, $service);
    }

    public function testRequestValidation()
    {
        $request = new StoreUserRequest();
        $data = ["name" => "", "email" => "invalido", "password" => "123", "active" => "invalido"];
        $this->expectException(\InvalidArgumentException::class);
        $request->validated($data);
    }

    public function testRequestValid()
    {
        $request = new StoreUserRequest();
        $data = ["name" => "Teste", "email" => "teste@teste.com", "password" => "Str0ng!Pass", "active" => true];
        $result = $request->validated($data);
        $this->assertIsArray($result);
        $this->assertEquals("Teste", $result["name"]);
    }
}
