<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy de User - Regras de autorizacao baseadas em nivel de acesso
 * 
 * Regras:
 * - ADMIN: Acesso total. Pode ver/editar/excluir/resetar senha de qualquer usuario,
 *          EXCETO editar/excluir outro admin (mas pode resetar senha de outro admin)
 * - OPERATIONAL: Pode ver usuarios operacional e suporte, resetar senha de ambos
 * - SUPPORT: Pode ver apenas outros suportes e resetar senha de suportes
 * - USER comum: So pode ver/editar a si mesmo
 */
class UserPolicy
{
    /**
     * Verifica se o usuario autenticado pode ver outro usuario
     */
    public function view(User $authenticatedUser, User $targetUser): bool
    {
        // Admin ve todos
        if ($this->isAdmin($authenticatedUser)) {
            return true;
        }

        // Operacional ve operacional e suporte
        if ($this->isOperational($authenticatedUser)) {
            return $this->isOperational($targetUser) || $this->isSupport($targetUser);
        }

        // Suporte ve apenas outros suportes
        if ($this->isSupport($authenticatedUser)) {
            return $this->isSupport($targetUser);
        }

        // Usuario comum so ve a si mesmo
        return $authenticatedUser->id === $targetUser->id;
    }

    /**
     * Verifica se pode criar usuario
     * Apenas administradores podem criar usuarios
     */
    public function create(User $authenticatedUser): bool
    {
        return $this->isAdmin($authenticatedUser);
    }

    /**
     * Verifica se pode atualizar um usuario
     * - Admin pode editar qualquer usuario EXCETO outro admin
     * - Usuario pode editar a si mesmo
     */
    public function update(User $authenticatedUser, User $targetUser): bool
    {
        // Admin nao pode editar outro admin
        if ($this->isAdmin($authenticatedUser) && $this->isAdmin($targetUser)) {
            return false;
        }

        // Administradores podem atualizar usuarios nao-admin
        if ($this->isAdmin($authenticatedUser)) {
            return true;
        }

        // Usuario so pode atualizar a si mesmo
        return $authenticatedUser->id === $targetUser->id;
    }

    /**
     * Verifica se pode resetar a senha de um usuario
     * - Admin pode resetar senha de qualquer usuario (inclusive outro admin)
     * - Operacional pode resetar senha de operacional e suporte
     * - Suporte pode resetar senha de outros suportes
     */
    public function resetPassword(User $authenticatedUser, User $targetUser): bool
    {
        // Admin pode resetar senha de qualquer um
        if ($this->isAdmin($authenticatedUser)) {
            return true;
        }

        // Operacional pode resetar senha de operacional e suporte
        if ($this->isOperational($authenticatedUser)) {
            return $this->isOperational($targetUser) || $this->isSupport($targetUser);
        }

        // Suporte pode resetar senha de outros suportes
        if ($this->isSupport($authenticatedUser)) {
            return $this->isSupport($targetUser);
        }

        return false;
    }

    /**
     * Verifica se pode deletar um usuario
     * - Apenas administradores podem deletar
     * - Admin NAO pode deletar a si mesmo
     * - Admin NAO pode deletar outro admin
     */
    public function delete(User $authenticatedUser, User $targetUser): bool
    {
        // Nao pode deletar a si mesmo
        if ($authenticatedUser->id === $targetUser->id) {
            return false;
        }

        // Admin nao pode deletar outro admin
        if ($this->isAdmin($authenticatedUser) && $this->isAdmin($targetUser)) {
            return false;
        }

        return $this->isAdmin($authenticatedUser);
    }

    private function isAdmin(User $user): bool
    {
        return $this->getUserLevel($user) === 'admin';
    }

    private function isOperational(User $user): bool
    {
        return $this->getUserLevel($user) === 'operational';
    }

    private function isSupport(User $user): bool
    {
        return $this->getUserLevel($user) === 'support';
    }

    private function getUserLevel(User $user): string
    {
        if (isset($user->level)) {
            return $user->level;
        }

        return 'user';
    }
}
