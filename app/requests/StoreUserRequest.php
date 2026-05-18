<?php

namespace App\Requests;

use App\Models\User;

/**
 * Validação para criação/atualização de usuário
 * Segue ISO 27001: sanitização e validação rigorosa de entradas
 */
class StoreUserRequest
{
    private ?int $ignoreId = null;

    public function __construct(?int $ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    /**
     * Valida e sanitiza os dados de entrada
     * 
     * @param array $data Dados a serem validados
     * @return array Dados validados e sanitizados
     * @throws \InvalidArgumentException
     */
    public function validated(array $data): array
    {
        $errors = [];
        $sanitized = [];

        // Validação de campos obrigatórios
        if (empty($data['name']) || trim($data['name']) === '') {
            $errors['name'] = 'O nome é obrigatório.';
        } elseif (strlen($data['name']) > 255) {
            $errors['name'] = 'O nome deve ter no máximo 255 caracteres.';
        }

        if (empty($data['email']) || trim($data['email']) === '') {
            $errors['email'] = 'O email é obrigatório.';
        } elseif (!validate_email($data['email'])) {
            $errors['email'] = 'Informe um email válido.';
        } elseif (strlen($data['email']) > 255) {
            $errors['email'] = 'O email deve ter no máximo 255 caracteres.';
        } else {
            // Validação unique:users,email real
            $query = User::where('email', sanitize_input($data['email']));
            if ($this->ignoreId) {
                $query->where('id', '!=', $this->ignoreId);
            }
            if ($query->exists()) {
                $errors['email'] = 'Este email já está cadastrado.';
            }
        }

        if (!isset($data['password']) || $data['password'] === '') {
            $errors['password'] = 'A senha é obrigatória.';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'A senha deve ter no mínimo 8 caracteres.';
        } elseif (strlen($data['password']) > 128) {
            $errors['password'] = 'A senha deve ter no máximo 128 caracteres.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/', $data['password'])) {
            $errors['password'] = 'A senha deve conter letras maiúsculas, minúsculas, números e caracteres especiais.';
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode(['errors' => $errors]));
        }

        // Sanitiza saída
        $sanitized['name'] = sanitize_input($data['name']);
        $sanitized['email'] = sanitize_input($data['email']);
        $sanitized['password'] = $data['password']; // será hasheado pelo mutator do model
        $sanitized['level'] = $data['level'] ?? 'operational';
        $sanitized['active'] = isset($data['active']) ? filter_var($data['active'], FILTER_VALIDATE_BOOLEAN) : true;

        return $sanitized;
    }
}
