e s<?php

namespace App\Requests;

/**
 * Validação para criação/atualização de produto
 * Segue ISO 27001: sanitização e validação rigorosa de entradas
 */
class StoreProductRequest
{
    /**
     * Regras de validação
     * @var array
     */
    private array $rules = [
        'name'        => 'required|string|max:255|sanitize',
        'description' => 'nullable|string|max:2000|sanitize',
        'price'       => 'required|numeric|min:0|max:999999.99',
        'category'    => 'nullable|string|max:100|sanitize',
        'active'      => 'boolean',
        'stock'       => 'nullable|integer|min:0|max:999999',
    ];

    /**
     * Mensagens de erro personalizadas
     * @var array
     */
    private array $messages = [
        'name.required'      => 'O nome do produto é obrigatório.',
        'name.max'           => 'O nome deve ter no máximo 255 caracteres.',
        'description.max'    => 'A descrição deve ter no máximo 2000 caracteres.',
        'price.required'     => 'O preço é obrigatório.',
        'price.numeric'      => 'O preço deve ser um valor numérico.',
        'price.min'          => 'O preço não pode ser negativo.',
        'price.max'          => 'O preço máximo é R$ 999.999,99.',
        'category.max'       => 'A categoria deve ter no máximo 100 caracteres.',
        'active.boolean'     => 'O status deve ser verdadeiro ou falso.',
        'stock.integer'      => 'O estoque deve ser um número inteiro.',
        'stock.min'          => 'O estoque não pode ser negativo.',
        'stock.max'          => 'O estoque máximo é 999.999 unidades.',
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
                if ($rule === 'sanitize' && is_string($value)) {
                    $value = sanitize_input($value);
                }

                if ($rule === 'required' && ($value === null || $value === '')) {
                    $errors[$field] = $this->messages["{$field}.required"] ?? "O campo {$field} é obrigatório.";
                    break;
                }

                if ($rule === 'nullable' && ($value === null || $value === '')) {
                    $sanitized[$field] = null;
                    continue 2;
                }

                if ($rule === 'string' && $value !== null && !is_string($value)) {
                    $errors[$field] = "O campo {$field} deve ser texto.";
                    break;
                }

                if ($rule === 'numeric' && $value !== null && !is_numeric($value)) {
                    $errors[$field] = "O campo {$field} deve ser numérico.";
                    break;
                }

                if ($rule === 'integer' && $value !== null && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $errors[$field] = $this->messages["{$field}.integer"] ?? "O campo {$field} deve ser inteiro.";
                    break;
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (float) substr($rule, 4);
                    if (is_numeric($value) && (float) $value < $min) {
                        $errors[$field] = $this->messages["{$field}.min"] ?? "O campo {$field} deve ser no mínimo {$min}.";
                        break;
                    }
                    if (is_string($value) && strlen($value) < $min) {
                        $errors[$field] = $this->messages["{$field}.min"] ?? "O campo {$field} deve ter no mínimo {$min} caracteres.";
                        break;
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (float) substr($rule, 4);
                    if (is_numeric($value) && (float) $value > $max) {
                        $errors[$field] = $this->messages["{$field}.max"] ?? "O campo {$field} deve ser no máximo {$max}.";
                        break;
                    }
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