<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Verifica se o usuário autenticado pode ver outro usuário
     */
    public function view(User $authenticatedUser, User $targetUser): bool
    {
        // Administradores podem ver qualquer usuário
        if ($this->isAdmin($authenticatedUser)) {
            return true;
        }

        // Usuário só pode ver a si mesmo
        return $authenticatedUser->id === $targetUser->id;
    }

    /**
     * Verifica se pode criar usuário
     */
    public function create(User $authenticatedUser): bool
    {
        // Apenas administradores podem criar usuários
        return $this->isAdmin($authenticatedUser);
    }

    /**
     * Verifica se pode atualizar um usuário
     */
    public function update(User $authenticatedUser, User $targetUser): bool
    {
        // Administradores podem atualizar qualquer usuário
        if ($this->isAdmin($authenticatedUser)) {
            return true;
        }

        // Usuário só pode atualizar a si mesmo
        return $authenticatedUser->id === $targetUser->id;
    }

    /**
     * Verifica se pode deletar um usuário
     */
    public function delete(User $authenticatedUser, User $targetUser): bool
    {
        // Administradores podem deletar qualquer usuário
        if ($this->isAdmin($authenticatedUser)) {
            return true;
        }

        // Usuário não pode se auto-deletar (apenas admin)
        return false;
    }

    /**
     * Verifica se é administrador
     * Role é armazenada no banco como campo 'role'
     */
    private function isAdmin(User $user): bool
    {
        return in_array($this->getUserRole($user), ['admin', 'superadmin']);
    }

    /**
     * Busca role do usuário (via atributo ou sessão)
     */
    private function getUserRole(User $user): string
    {
        // O Eloquent busca direto do banco se não estiver em memória
        if (isset($user->role)) {
            return $user->role;
        }

        return 'user';
    }
}