<?php

namespace App\Requests;

/**
 * Validação para criação/atualização de motorista
 * Segue ISO 27001: sanitização e validação rigorosa de entradas
 */
class StoreDriverRequest
{
    private array $rules = [
        'nome'           => 'required|string|max:255|sanitize',
        'cpf'            => 'required|string|max:14|sanitize',
        'rg'             => 'string|max:20|sanitize',
        'cnh'            => 'required|string|max:20|sanitize',
        'categoria_cnh'  => 'required|string|max:5|sanitize',
        'endereco'       => 'string|max:255|sanitize',
        'bairro'         => 'string|max:100|sanitize',
        'cidade'         => 'string|max:100|sanitize',
        'estado'         => 'string|max:2|sanitize',
        'cep'            => 'string|max:10|sanitize',
        'toxicologico'   => 'boolean',
        'pendencias'     => 'boolean',
    ];

    private array $messages = [
        'nome.required'          => 'O nome é obrigatório.',
        'nome.max'               => 'O nome deve ter no máximo 255 caracteres.',
        'cpf.required'           => 'O CPF é obrigatório.',
        'cpf.max'                => 'O CPF deve ter no máximo 14 caracteres.',
        'rg.max'                 => 'O RG deve ter no máximo 20 caracteres.',
        'cnh.required'           => 'A CNH é obrigatória.',
        'cnh.max'                => 'A CNH deve ter no máximo 20 caracteres.',
        'categoria_cnh.required' => 'A categoria da CNH é obrigatória.',
        'categoria_cnh.max'      => 'A categoria da CNH deve ter no máximo 5 caracteres.',
        'endereco.max'           => 'O endereço deve ter no máximo 255 caracteres.',
        'bairro.max'             => 'O bairro deve ter no máximo 100 caracteres.',
        'cidade.max'             => 'A cidade deve ter no máximo 100 caracteres.',
        'estado.max'             => 'O estado deve ter no máximo 2 caracteres.',
        'cep.max'                => 'O CEP deve ter no máximo 10 caracteres.',
        'toxicologico.boolean'   => 'O campo toxicológico deve ser verdadeiro ou falso.',
        'pendencias.boolean'     => 'O campo pendências deve ser verdadeiro ou falso.',
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

                if ($rule === 'boolean' && $value !== null) {
                    if (!in_array($value, [true, false, 0, 1, '0', '1'], true)) {
                        $errors[$field] = $this->messages["{$field}.boolean"] ?? "O campo {$field} deve ser verdadeiro ou falso.";
                        break;
                    }
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
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
