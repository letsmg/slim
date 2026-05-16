<?php

namespace App\Requests;

/**
 * Validação para criação/atualização de veículo
 * Segue ISO 27001: sanitização e validação rigorosa de entradas
 */
class StoreVehicleRequest
{
    private array $rules = [
        'marca'             => 'required|string|max:100|sanitize',
        'modelo'            => 'required|string|max:100|sanitize',
        'eixos'             => 'integer|min:2|max:20',
        'crlv'              => 'required|string|max:50|sanitize',
        'tipo_combustivel'  => 'required|string|max:50|sanitize',
        'dt_ultima_revisao' => 'date',
        'dt_proxima_revisao'=> 'date',
        'dt_compra'         => 'date',
    ];

    private array $messages = [
        'marca.required'            => 'A marca é obrigatória.',
        'marca.max'                 => 'A marca deve ter no máximo 100 caracteres.',
        'modelo.required'           => 'O modelo é obrigatório.',
        'modelo.max'                => 'O modelo deve ter no máximo 100 caracteres.',
        'eixos.integer'             => 'O número de eixos deve ser um valor inteiro.',
        'eixos.min'                 => 'O número mínimo de eixos é 2.',
        'eixos.max'                 => 'O número máximo de eixos é 20.',
        'crlv.required'             => 'O CRLV é obrigatório.',
        'crlv.max'                  => 'O CRLV deve ter no máximo 50 caracteres.',
        'tipo_combustivel.required' => 'O tipo de combustível é obrigatório.',
        'tipo_combustivel.max'      => 'O tipo de combustível deve ter no máximo 50 caracteres.',
        'dt_ultima_revisao.date'    => 'Informe uma data válida para última revisão.',
        'dt_proxima_revisao.date'   => 'Informe uma data válida para próxima revisão.',
        'dt_compra.date'            => 'Informe uma data válida para compra.',
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

                if ($rule === 'integer' && $value !== null && $value !== '') {
                    if (!filter_var($value, FILTER_VALIDATE_INT)) {
                        $errors[$field] = $this->messages["{$field}.integer"] ?? "O campo {$field} deve ser um número inteiro.";
                        break;
                    }
                    $value = (int) $value;
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if (is_numeric($value) && $value < $min) {
                        $errors[$field] = $this->messages["{$field}.min"] ?? "O campo {$field} deve ser no mínimo {$min}.";
                        break;
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (is_string($value) && strlen($value) > $max) {
                        $errors[$field] = $this->messages["{$field}.max"] ?? "O campo {$field} deve ter no máximo {$max} caracteres.";
                        break;
                    }
                }

                if ($rule === 'date' && $value !== null && $value !== '') {
                    $d = \DateTime::createFromFormat('Y-m-d', $value);
                    if (!$d || $d->format('Y-m-d') !== $value) {
                        $errors[$field] = $this->messages["{$field}.date"] ?? "Informe uma data válida (AAAA-MM-DD).";
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
