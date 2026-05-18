<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'plate',
        'axles',
        'crlv',
        'chassis',
        'renavam',
        'fuel_type',
        'last_maintenance_date',
        'next_maintenance_date',
        'purchase_date',
        'photo1',
        'photo2',
        'photo3',
    ];

    protected $casts = [
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'purchase_date' => 'date',
    ];
}
