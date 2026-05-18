<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Verifica se o usuário autenticado pode ver outro usuário
     * Admin: vê todos
     * Operacional: vê apenas outros operacionais
     */
    public function view(User $authenticatedUser, User $targetUser): bool
    {
        // Administradores podem ver qualquer usuário
        if ($this->isAdmin($authenticatedUser)) {
            return true;
        }

        // Operacional só vê outros operacionais
        if ($this->isOperational($authenticatedUser)) {
            return $this->isOperational($targetUser);
        }

        // Usuário só pode ver a si mesmo
        return $authenticatedUser->id === $targetUser->id;
    }

    /**
     * Verifica se pode criar usuário
     * Apenas administradores podem criar usuários
     */
    public function create(User $authenticatedUser): bool
    {
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
     * Apenas administradores podem deletar
     */
    public function delete(User $authenticatedUser, User $targetUser): bool
    {
        return $this->isAdmin($authenticatedUser);
    }

    /**
     * Verifica se é administrador
     */
    private function isAdmin(User $user): bool
    {
        return $this->getUserLevel($user) === 'admin';
    }

    /**
     * Verifica se é operacional
     */
    private function isOperational(User $user): bool
    {
        return $this->getUserLevel($user) === 'operational';
    }

    /**
     * Busca level do usuário
     */
    private function getUserLevel(User $user): string
    {
        if (isset($user->level)) {
            return $user->level;
        }

        return 'user';
    }
}
