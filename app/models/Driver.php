<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'cpf',
        'rg',
        'cnh',
        'cnh_category',
        'address',
        'neighborhood',
        'city',
        'state',
        'zipcode',
        'toxicological',
        'pending_issues',
        'photo',
    ];

    protected $casts = [
        'toxicological' => 'boolean',
        'pending_issues' => 'boolean',
    ];
}
