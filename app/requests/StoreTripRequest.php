<?php

namespace App\Requests;

use App\Models\Trip;

/**
 * Validação para criação/atualização de viagem
 * Segue ISO 27001: sanitização e validação rigorosa de entradas
 * Regras de jornada de trabalho:
 * 1- Soma das viagens > 8h se intervalo < 1h entre viagens
 * 2- Soma das viagens > 10h se intervalo < 2h entre viagens
 * 3- Nunca pode ultrapassar 10h de soma total
 */
class StoreTripRequest
{
    private array $rules = [
        'driver_id'          => 'required|integer',
        'vehicle_id'         => 'required|integer',
        'origin'             => 'required|string|max:255|sanitize',
        'destination'        => 'required|string|max:255|sanitize',
        'departure_forecast' => 'required|datetime',
        'arrival_forecast'   => 'required|datetime',
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
        'arrival_forecast.required'   => 'A previsão de chegada é obrigatória.',
        'arrival_forecast.datetime'   => 'Informe uma data/hora válida para chegada.',
        'status.required'             => 'O status é obrigatório.',
        'status.max'                  => 'O status deve ter no máximo 20 caracteres.',
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

        // Valida regras de jornada de trabalho
        if (empty($errors) && !empty($data['driver_id']) && !empty($data['departure_forecast']) && !empty($data['arrival_forecast'])) {
            $this->validateWorkHours($data, $errors);
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode(['errors' => $errors]));
        }

        return $sanitized;
    }

    /**
     * Valida as regras de jornada de trabalho do motorista
     * 
     * Regras:
     * 1- Soma das viagens > 8h se intervalo < 1h entre viagens consecutivas
     * 2- Soma das viagens > 10h se intervalo < 2h entre viagens consecutivas
     * 3- Nunca pode ultrapassar 10h de soma total
     * 
     * A validação considera a sequência real ordenada por departure_forecast,
     * inserindo a nova viagem na posição correta para verificar intervalos
     * entre viagens consecutivas.
     */
    private function validateWorkHours(array $data, array &$errors): void
    {
        $driverId = (int) $data['driver_id'];
        $newDeparture = new \DateTime($data['departure_forecast']);
        $newArrival = new \DateTime($data['arrival_forecast']);

        // Duração da nova viagem em horas
        $newDuration = ($newArrival->getTimestamp() - $newDeparture->getTimestamp()) / 3600;

        if ($newDuration <= 0) {
            $errors['arrival_forecast'] = 'A previsão de chegada deve ser posterior à previsão de saída.';
            return;
        }

        // Busca viagens existentes do motorista (não canceladas)
        $query = Trip::where('driver_id', $driverId)
            ->whereIn('status', ['scheduled', 'in_progress', 'completed']);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        $existingTrips = $query->orderBy('departure_forecast', 'asc')->get();

        // Monta lista de blocos de tempo (início, fim) ordenados
        $blocks = [];
        foreach ($existingTrips as $trip) {
            $blocks[] = [
                'start' => new \DateTime($trip->departure_forecast),
                'end'   => new \DateTime($trip->arrival_forecast),
            ];
        }

        // Insere a nova viagem na posição correta (ordenada por departure)
        $inserted = false;
        for ($i = 0; $i < count($blocks); $i++) {
            if ($newDeparture < $blocks[$i]['start']) {
                array_splice($blocks, $i, 0, [[
                    'start' => $newDeparture,
                    'end'   => $newArrival,
                ]]);
                $inserted = true;
                break;
            }
        }
        if (!$inserted) {
            $blocks[] = [
                'start' => $newDeparture,
                'end'   => $newArrival,
            ];
        }

        // Calcula soma total de horas e verifica intervalos entre blocos consecutivos
        $totalHours = 0;
        $minInterval = PHP_FLOAT_MAX;

        for ($i = 0; $i < count($blocks); $i++) {
            $duration = ($blocks[$i]['end']->getTimestamp() - $blocks[$i]['start']->getTimestamp()) / 3600;
            $totalHours += $duration;

            // Calcula intervalo entre esta viagem e a próxima
            if ($i < count($blocks) - 1) {
                $gap = ($blocks[$i + 1]['start']->getTimestamp() - $blocks[$i]['end']->getTimestamp()) / 3600;
                if ($gap < $minInterval) {
                    $minInterval = $gap;
                }
            }
        }

        // Regra 3: Soma total nunca pode ultrapassar 10h
        if ($totalHours > 10) {
            $errors['departure_forecast'] = sprintf(
                'Jornada excede 10 horas totais (%.1f h). Não é possível cadastrar esta viagem.',
                $totalHours
            );
            return;
        }

        // Regra 1: Soma > 8h se intervalo < 1h entre viagens consecutivas
        if ($totalHours > 8 && $minInterval < 1) {
            $errors['departure_forecast'] = sprintf(
                'Jornada ultrapassa 8 horas (%.1f h) com intervalo de apenas %.1f h entre viagens consecutivas. ' .
                'É necessário intervalo mínimo de 1 hora.',
                $totalHours,
                $minInterval
            );
            return;
        }

        // Regra 2: Soma > 10h se intervalo < 2h entre viagens consecutivas
        if ($totalHours > 10 && $minInterval < 2) {
            $errors['departure_forecast'] = sprintf(
                'Jornada ultrapassa 10 horas (%.1f h) com intervalo de apenas %.1f h entre viagens consecutivas. ' .
                'É necessário intervalo mínimo de 2 horas.',
                $totalHours,
                $minInterval
            );
            return;
        }
    }
}
