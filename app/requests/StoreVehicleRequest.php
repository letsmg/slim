<?php

namespace App\Requests;

use App\Models\Vehicle;

/**
 * Validacao para criacao/atualizacao de veiculo
 * Segue ISO 27001: sanitizacao e validacao rigorosa de entradas
 * Valida unicidade de plate, chassis e renavam
 * Campos em ingles conforme regra do projeto (.clinerules)
 */
class StoreVehicleRequest
{
    private array $rules = [
        'brand'                 => 'required|string|max:100|sanitize',
        'model'                 => 'required|string|max:100|sanitize',
        'plate'                 => 'string|max:10|sanitize',
        'axles'                 => 'integer|min:2|max:20',
        'crlv'                  => 'required|string|max:50|sanitize',
        'chassis'               => 'string|max:50|sanitize',
        'renavam'               => 'string|max:50|sanitize',
        'fuel_type'             => 'required|string|max:50|sanitize',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'purchase_date'         => 'date',
        'photo1'                => 'string',
        'photo2'                => 'string',
        'photo3'                => 'string',
        'photo4'                => 'string',
        'photo5'                => 'string',
        'photo6'                => 'string',
        'antt_photo'            => 'string',
    ];

    private array $messages = [
        'brand.required'                 => 'A marca e obrigatoria.',
        'brand.max'                      => 'A marca deve ter no maximo 100 caracteres.',
        'model.required'                 => 'O modelo e obrigatorio.',
        'model.max'                      => 'O modelo deve ter no maximo 100 caracteres.',
        'plate.max'                      => 'A placa deve ter no maximo 10 caracteres.',
        'axles.integer'                  => 'O numero de eixos deve ser um valor inteiro.',
        'axles.min'                      => 'O numero minimo de eixos e 2.',
        'axles.max'                      => 'O numero maximo de eixos e 20.',
        'crlv.required'                  => 'O CRLV e obrigatorio.',
        'crlv.max'                       => 'O CRLV deve ter no maximo 50 caracteres.',
        'chassis.max'                    => 'O chassi deve ter no maximo 50 caracteres.',
        'renavam.max'                    => 'O Renavam deve ter no maximo 50 caracteres.',
        'fuel_type.required'             => 'O tipo de combustivel e obrigatorio.',
        'fuel_type.max'                  => 'O tipo de combustivel deve ter no maximo 50 caracteres.',
        'last_maintenance_date.date'     => 'Informe uma data valida para ultima revisao.',
        'next_maintenance_date.date'     => 'Informe uma data valida para proxima revisao.',
        'purchase_date.date'             => 'Informe uma data valida para compra.',
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

                if ($rule === 'integer' && $value !== null && $value !== '') {
                    if (!filter_var($value, FILTER_VALIDATE_INT)) {
                        $errors[$field] = $this->messages["{$field}.integer"] ?? "O campo {$field} deve ser um numero inteiro.";
                        break;
                    }
                    $value = (int) $value;
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if (is_numeric($value) && $value < $min) {
                        $errors[$field] = $this->messages["{$field}.min"] ?? "O campo {$field} deve ser no minimo {$min}.";
                        break;
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (is_string($value) && strlen($value) > $max) {
                        $errors[$field] = $this->messages["{$field}.max"] ?? "O campo {$field} deve ter no maximo {$max} caracteres.";
                        break;
                    }
                }

                if ($rule === 'date' && $value !== null && $value !== '') {
                    $d = \DateTime::createFromFormat('Y-m-d', $value);
                    if (!$d || $d->format('Y-m-d') !== $value) {
                        $errors[$field] = $this->messages["{$field}.date"] ?? "Informe uma data valida (AAAA-MM-DD).";
                        break;
                    }
                }
            }

            if (!isset($errors[$field])) {
                $sanitized[$field] = $value;
            }
        }

        // Valida unicidade de plate, chassis e renavam
        $uniqueFields = ['plate', 'chassis', 'renavam'];
        foreach ($uniqueFields as $field) {
            if (!empty($data[$field])) {
                $query = Vehicle::where($field, $data[$field]);
                if ($this->ignoreId) {
                    $query->where('id', '!=', $this->ignoreId);
                }
                if ($query->exists()) {
                    $labels = [
                        'plate' => 'placa',
                        'chassis' => 'chassi',
                        'renavam' => 'renavam',
                    ];
                    $errors[$field] = "Este(a) {$labels[$field]} ja esta cadastrado(a) em outro veiculo.";
                }
            }
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode(['errors' => $errors]));
        }

        return $sanitized;
    }
}
