<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Cria um novo usuário com dados sanitizados e senha hasheada com Argon2id
     */
    public function create(array $data): User
    {
        $data = sanitize_inputs($data);

        if (!validate_email($data['email'] ?? '')) {
            throw new \InvalidArgumentException('Email inválido');
        }

        return $this->userRepository->create($data);
    }

    /**
     * Autentica usuário com verificação timing-safe de senha Argon2id
     * Aplica rehash automático se a configuração do Argon2id mudou
     */
    public function authenticate(string $email, string $password): ?User
    {
        $email = sanitize_input($email);
        $user = $this->userRepository->findByEmail($email);

        if ($user === null) {
            return null;
        }

        if (!$user->verifyPassword($password)) {
            return null;
        }

        // Rehash automático: se os parâmetros do Argon2id mudaram, atualiza
        if ($user->passwordNeedsRehash()) {
            $this->userRepository->update($user->id, [
                'password' => $password, // o mutator do model aplica hash
            ]);
        }

        return $user;
    }

    /**
     * Busca usuário por ID com saída escapada
     */
    public function findById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Lista todos os usuários
     */
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * Atualiza dados do usuário com sanitização
     */
    public function update(int $id, array $data): ?User
    {
        $data = sanitize_inputs($data);

        if (isset($data['email']) && !validate_email($data['email'])) {
            throw new \InvalidArgumentException('Email inválido');
        }

        return $this->userRepository->update($id, $data);
    }

    /**
     * Remove um usuário
     */
    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
}