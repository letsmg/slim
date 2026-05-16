<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'nome',
        'cpf',
        'rg',
        'cnh',
        'categoria_cnh',
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'toxicologico',
        'pendencias',
    ];

    protected $casts = [
        'toxicologico' => 'boolean',
        'pendencias' => 'boolean',
    ];
}
