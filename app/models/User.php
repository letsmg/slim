<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'active',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Mutator: aplica hash Argon2id automaticamente ao setar a senha
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = hash_password($value);
    }

    /**
     * Verifica se a senha fornecida corresponde ao hash armazenado
     */
    public function verifyPassword(string $password): bool
    {
        return verify_password($password, $this->attributes['password']);
    }

    /**
     * Verifica se o hash atual precisa ser atualizado (cost aumentou, etc.)
     */
    public function passwordNeedsRehash(): bool
    {
        return needs_rehash($this->attributes['password']);
    }
}