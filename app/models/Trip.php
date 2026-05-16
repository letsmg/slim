<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Trip - Representa uma viagem
 * Tabela: trips
 * Relaciona motorista e veiculo
 */
class Trip extends Model
{
    protected $table = 'trips';

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'departure_forecast',
        'arrival_forecast',
        'origin',
        'destination',
        'status',
    ];

    protected $casts = [
        'departure_forecast' => 'datetime',
        'arrival_forecast' => 'datetime',
    ];

    /**
     * Relacionamento: viagem pertence a um motorista
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Relacionamento: viagem pertence a um veiculo
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
