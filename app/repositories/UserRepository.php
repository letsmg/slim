<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Capsule\Manager as DB;

class UserRepository
{
    /**
     * Cria um novo usuário (dados já sanitizados pelo Service)
     */
    public function create(array $data): User
    {
        // Usa Query Builder do Eloquent (protegido contra SQL injection)
        return User::create($data);
    }

    /**
     * Busca usuário por ID
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Busca usuário por email (com prepared statement)
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Lista todos os usuários
     */
    public function findAll(): array
    {
        return User::all()->all();
    }

    /**
     * Atualiza dados do usuário
     */
    public function update(int $id, array $data): ?User
    {
        $user = User::find($id);
        if ($user === null) {
            return null;
        }
        $user->fill($data);
        $user->save();
        return $user;
    }

    /**
     * Remove um usuário
     */
    public function delete(int $id): bool
    {
        $user = User::find($id);
        if ($user === null) {
            return false;
        }
        return (bool) $user->delete();
    }
}