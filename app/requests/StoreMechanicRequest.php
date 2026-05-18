<?php

namespace App\Requests;

/**
 * Validação para criação/atualização de mecânico
 * Segue ISO 27001: sanitização e validação rigorosa de entradas
 */
class StoreMechanicRequest
{
    private array $rules = [
        'name'     => 'required|string|max:255|sanitize',
        'address'  => 'string|max:255|sanitize',
        'document' => 'required|string|max:20|sanitize',
        'phone1'   => 'required|string|max:20|sanitize',
        'phone2'   => 'string|max:20|sanitize',
    ];

    private array $messages = [
        'name.required'     => 'O nome é obrigatório.',
        'name.max'          => 'O nome deve ter no máximo 255 caracteres.',
        'address.max'       => 'O endereço deve ter no máximo 255 caracteres.',
        'document.required' => 'O documento é obrigatório.',
        'document.max'      => 'O documento deve ter no máximo 20 caracteres.',
        'phone1.required'   => 'O telefone 1 é obrigatório.',
        'phone1.max'        => 'O telefone 1 deve ter no máximo 20 caracteres.',
        'phone2.max'        => 'O telefone 2 deve ter no máximo 20 caracteres.',
    ];

    public function validated(array $data): array
    {
        $errors = [];
        $sanitized = [];

        foreach ($this->rules as $field => $rules) {
            $fieldRules = explode('|', $rules);
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                if ($rule === 'sanitize' && is_string($value)) {
                    $value = sanitize_input($value);
                }

                if ($rule === 'required' && ($value === null || $value === '')) {
                    $errors[$field] = $this->messages["{$field}.required"] ?? "O campo {$field} é obrigatório.";
                    break;
                }

                if ($rule === 'string' && $value !== null && !is_string($value)) {
                    $errors[$field] = "O campo {$field} deve ser texto.";
                    break;
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (is_string($value) && strlen($value) > $max) {
                        $errors[$field] = $this->messages["{$field}.max"] ?? "O campo {$field} deve ter no máximo {$max} caracteres.";
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
