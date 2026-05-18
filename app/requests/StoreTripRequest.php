<?php

namespace App\Requests;

/**
 * Validação para criação/atualização de viagem
 * Segue ISO 27001: sanitização e validação rigorosa de entradas
 */
class StoreTripRequest
{
    private array $rules = [
        'driver_id'          => 'required|integer',
        'vehicle_id'         => 'required|integer',
        'origin'             => 'required|string|max:255|sanitize',
        'destination'        => 'required|string|max:255|sanitize',
        'departure_forecast' => 'required|datetime',
        'arrival_forecast'   => 'datetime',
        'status'             => 'required|string|max:20|sanitize',
    ];

    private array $messages = [
        'driver_id.required'          => 'O motorista é obrigatório.',
        'driver_id.integer'           => 'O motorista deve ser um ID válido.',
        'vehicle_id.required'         => 'O veículo é obrigatório.',
        'vehicle_id.integer'          => 'O veículo deve ser um ID válido.',
        'origin.required'             => 'A origem é obrigatória.',
        'origin.max'                  => 'A origem deve ter no máximo 255 caracteres.',
        'destination.required'        => 'O destino é obrigatório.',
        'destination.max'             => 'O destino deve ter no máximo 255 caracteres.',
        'departure_forecast.required' => 'A previsão de saída é obrigatória.',
        'departure_forecast.datetime' => 'Informe uma data/hora válida para saída.',
        'arrival_forecast.datetime'   => 'Informe uma data/hora válida para chegada.',
        'status.required'             => 'O status é obrigatório.',
        'status.max'                  => 'O status deve ter no máximo 20 caracteres.',
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

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (is_string($value) && strlen($value) > $max) {
                        $errors[$field] = $this->messages["{$field}.max"] ?? "O campo {$field} deve ter no máximo {$max} caracteres.";
                        break;
                    }
                }

                if ($rule === 'datetime' && $value !== null && $value !== '') {
                    $d = \DateTime::createFromFormat('Y-m-d\TH:i', $value);
                    if (!$d) {
                        $d = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
                    }
                    if (!$d) {
                        $errors[$field] = $this->messages["{$field}.datetime"] ?? "Informe uma data/hora válida.";
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
