<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model ScheduledMaintenance - Representa uma manutencao programada
 * Tabela: scheduled_maintenances
 * Relaciona motorista, veiculo e mecanico
 */
class ScheduledMaintenance extends Model
{
    protected $table = 'scheduled_maintenances';

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'mechanic_id',
        'scheduled_date',
        'scheduled_time',
        'contact',
        'service',
        'observations',
        'completed',
        'paid',
        'photo1',
        'photo2',
        'photo3',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'string',
        'completed' => 'boolean',
        'paid' => 'boolean',
    ];

    /**
     * Relacionamento: manutencao pertence a um motorista
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Relacionamento: manutencao pertence a um veiculo
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Relacionamento: manutencao pertence a um mecanico
     */
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }
}
