<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'marca',
        'modelo',
        'placa',
        'eixos',
        'crlv',
        'chassi',
        'renavam',
        'tipo_combustivel',
        'dt_ultima_revisao',
        'dt_proxima_revisao',
        'dt_compra',
        'photo1',
        'photo2',
        'photo3',
    ];

    protected $casts = [
        'dt_ultima_revisao' => 'date',
        'dt_proxima_revisao' => 'date',
        'dt_compra' => 'date',
    ];
}
