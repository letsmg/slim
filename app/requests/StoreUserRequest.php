<?php

namespace App\Requests;

/**
 * Validação para criação/atualização de usuário
 * Segue ISO 27001: sanitização e validação rigorosa de entradas
 */
class StoreUserRequest
{
    /**
     * Regras de validação
     * @var array
     */
    private array $rules = [
        'name'     => 'required|string|max:255|sanitize',
        'email'    => 'required|email|max:255|sanitize|unique:users,email',
        'password' => 'required|string|min:8|max:128|strong_password',
        'active'   => 'boolean',
    ];

    /**
     * Mensagens de erro personalizadas
     * @var array
     */
    private array $messages = [
        'name.required'          => 'O nome é obrigatório.',
        'name.max'               => 'O nome deve ter no máximo 255 caracteres.',
        'email.required'         => 'O email é obrigatório.',
        'email.email'            => 'Informe um email válido.',
        'email.unique'           => 'Este email já está cadastrado.',
        'password.required'      => 'A senha é obrigatória.',
        'password.min'           => 'A senha deve ter no mínimo 8 caracteres.',
        'password.max'           => 'A senha deve ter no máximo 128 caracteres.',
        'password.strong_password' => 'A senha deve conter letras maiúsculas, minúsculas, números e caracteres especiais.',
        'active.boolean'         => 'O status deve ser verdadeiro ou falso.',
    ];

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

        foreach ($this->rules as $field => $rules) {
            $fieldRules = explode('|', $rules);
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                // Sanitização
                if ($rule === 'sanitize' && is_string($value)) {
                    $value = sanitize_input($value);
                }

                // Validação required
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $errors[$field] = $this->messages["{$field}.required"] ?? "O campo {$field} é obrigatório.";
                    break;
                }

                // Validação string
                if ($rule === 'string' && $value !== null && !is_string($value)) {
                    $errors[$field] = "O campo {$field} deve ser texto.";
                    break;
                }

                // Validação email
                if ($rule === 'email' && $value !== null && $value !== '') {
                    if (!validate_email($value)) {
                        $errors[$field] = $this->messages["{$field}.email"] ?? "Informe um email válido.";
                        break;
                    }
                }

                // Validação min length
                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if (is_string($value) && strlen($value) < $min) {
                        $errors[$field] = $this->messages["{$field}.min"] ?? "O campo {$field} deve ter no mínimo {$min} caracteres.";
                        break;
                    }
                }

                // Validação max length
                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (is_string($value) && strlen($value) > $max) {
                        $errors[$field] = $this->messages["{$field}.max"] ?? "O campo {$field} deve ter no máximo {$max} caracteres.";
                        break;
                    }
                }

                // Validação boolean
                if ($rule === 'boolean' && $value !== null) {
                    if (!in_array($value, [true, false, 0, 1, '0', '1'], true)) {
                        $errors[$field] = $this->messages["{$field}.boolean"] ?? "O campo {$field} deve ser verdadeiro ou falso.";
                        break;
                    }
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }

                // Validação strong_password
                if ($rule === 'strong_password' && is_string($value) && $value !== '') {
                    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/', $value)) {
                        $errors[$field] = $this->messages["{$field}.strong_password"];
                        break;
                    }
                }
            }

            if (!isset($errors[$field])) {
                $sanitized[$field] = $value;
            }
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode(['errors' => $errors]));
        }

        return $sanitized;
    }
}