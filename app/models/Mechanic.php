<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Mechanic - Representa um mecânico
 * Tabela: mechanics
 */
class Mechanic extends Model
{
    protected $table = 'mechanics';

    protected $fillable = [
        'name',
        'address',
        'document',
        'phone1',
        'phone2',
    ];
}
