<?php

namespace App\Requests;

/**
 * Validação para criação/atualização de manutenção programada
 * Segue ISO 27001: sanitização e validação rigorosa de entradas
 */
class StoreScheduledMaintenanceRequest
{
    private array $rules = [
        'driver_id'      => 'required|integer',
        'vehicle_id'     => 'required|integer',
        'mechanic_id'    => 'required|integer',
        'scheduled_date' => 'required|date',
        'scheduled_time' => 'time',
        'contact'        => 'string|max:20|sanitize',
        'service'        => 'required|string|max:255|sanitize',
        'observations'   => 'string|max:500|sanitize',
        'completed'      => 'boolean',
        'paid'           => 'boolean',
    ];

    private array $messages = [
        'driver_id.required'      => 'O motorista é obrigatório.',
        'driver_id.integer'       => 'O motorista deve ser um ID válido.',
        'vehicle_id.required'     => 'O veículo é obrigatório.',
        'vehicle_id.integer'      => 'O veículo deve ser um ID válido.',
        'mechanic_id.required'    => 'O mecânico é obrigatório.',
        'mechanic_id.integer'     => 'O mecânico deve ser um ID válido.',
        'scheduled_date.required' => 'A data agendada é obrigatória.',
        'scheduled_date.date'     => 'Informe uma data válida (AAAA-MM-DD).',
        'scheduled_time.time'     => 'Informe um horário válido (HH:MM).',
        'contact.max'             => 'O contato deve ter no máximo 20 caracteres.',
        'service.required'        => 'O serviço é obrigatório.',
        'service.max'             => 'O serviço deve ter no máximo 255 caracteres.',
        'observations.max'        => 'As observações devem ter no máximo 500 caracteres.',
        'completed.boolean'       => 'O campo realizado deve ser verdadeiro ou falso.',
        'paid.boolean'            => 'O campo pago deve ser verdadeiro ou falso.',
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

                if ($rule === 'date' && $value !== null && $value !== '') {
                    $d = \DateTime::createFromFormat('Y-m-d', $value);
                    if (!$d || $d->format('Y-m-d') !== $value) {
                        $errors[$field] = $this->messages["{$field}.date"] ?? "Informe uma data válida (AAAA-MM-DD).";
                        break;
                    }
                }

                if ($rule === 'time' && $value !== null && $value !== '') {
                    $d = \DateTime::createFromFormat('H:i', $value);
                    if (!$d) {
                        $d = \DateTime::createFromFormat('H:i:s', $value);
                    }
                    if (!$d) {
                        $errors[$field] = $this->messages["{$field}.time"] ?? "Informe um horário válido (HH:MM).";
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
