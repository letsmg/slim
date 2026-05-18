<?php

namespace App\Requests;

use App\Models\Driver;

/**
 * Validacao para criacao/atualizacao de motorista
 * Segue ISO 27001: sanitizacao e validacao rigorosa de entradas
 * Valida unicidade de CPF
 * Campos em ingles conforme regra do projeto (.clinerules)
 */
class StoreDriverRequest
{
    private array $rules = [
        'name'           => 'required|string|max:255|sanitize',
        'cpf'            => 'required|string|max:14|sanitize',
        'rg'             => 'string|max:20|sanitize',
        'cnh'            => 'required|string|max:20|sanitize',
        'cnh_category'   => 'required|string|max:5|sanitize',
        'address'        => 'string|max:255|sanitize',
        'neighborhood'   => 'string|max:100|sanitize',
        'city'           => 'string|max:100|sanitize',
        'state'          => 'string|max:2|sanitize',
        'zipcode'        => 'string|max:10|sanitize',
        'toxicological'       => 'boolean',
        'pending_issues'      => 'boolean',
        'photo'               => 'string',
        'cnh_photo'           => 'string',
        'toxicological_photo' => 'string',
        'nr35_photo'          => 'string',
        'nr20_photo'          => 'string',
    ];

    private array $messages = [
        'name.required'           => 'O nome e obrigatorio.',
        'name.max'                => 'O nome deve ter no maximo 255 caracteres.',
        'cpf.required'            => 'O CPF e obrigatorio.',
        'cpf.max'                 => 'O CPF deve ter no maximo 14 caracteres.',
        'rg.max'                  => 'O RG deve ter no maximo 20 caracteres.',
        'cnh.required'            => 'A CNH e obrigatoria.',
        'cnh.max'                 => 'A CNH deve ter no maximo 20 caracteres.',
        'cnh_category.required'   => 'A categoria da CNH e obrigatoria.',
        'cnh_category.max'        => 'A categoria da CNH deve ter no maximo 5 caracteres.',
        'address.max'             => 'O endereco deve ter no maximo 255 caracteres.',
        'neighborhood.max'        => 'O bairro deve ter no maximo 100 caracteres.',
        'city.max'                => 'A cidade deve ter no maximo 100 caracteres.',
        'state.max'               => 'O estado deve ter no maximo 2 caracteres.',
        'zipcode.max'             => 'O CEP deve ter no maximo 10 caracteres.',
        'toxicological.boolean'   => 'O campo toxicologico deve ser verdadeiro ou falso.',
        'pending_issues.boolean'  => 'O campo pendencias deve ser verdadeiro ou falso.',
    ];

    private ?int $ignoreId = null;

    public function __construct(?int $ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

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
                    $errors[$field] = $this->messages["{$field}.required"] ?? "O campo {$field} e obrigatorio.";
                    break;
                }

                if ($rule === 'string' && $value !== null && !is_string($value)) {
                    $errors[$field] = "O campo {$field} deve ser texto.";
                    break;
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (is_string($value) && strlen($value) > $max) {
                        $errors[$field] = $this->messages["{$field}.max"] ?? "O campo {$field} deve ter no maximo {$max} caracteres.";
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

        // Valida unicidade de CPF
        if (!empty($data['cpf'])) {
            $query = Driver::where('cpf', $data['cpf']);
            if ($this->ignoreId) {
                $query->where('id', '!=', $this->ignoreId);
            }
            if ($query->exists()) {
                $errors['cpf'] = 'Este CPF ja esta cadastrado para outro motorista.';
            }
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode(['errors' => $errors]));
        }

        return $sanitized;
    }
}
