<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Adiciona campos chassi e renavam na tabela vehicles
 * Adiciona índices únicos para placa (crlv), chassi e renavam
 * Adiciona índice único para cpf na tabela drivers
 */
final class AddChassiRenavamToVehicles extends AbstractMigration
{
    public function change(): void
    {
        // --- Vehicles ---
        $table = $this->table('vehicles');

        // Adiciona campos chassi e renavam
        if (!$table->hasColumn('chassi')) {
            $table->addColumn('chassi', 'string', ['limit' => 50, 'null' => true, 'after' => 'crlv']);
        }
        if (!$table->hasColumn('renavam')) {
            $table->addColumn('renavam', 'string', ['limit' => 50, 'null' => true, 'after' => 'chassi']);
        }
        if (!$table->hasColumn('placa')) {
            $table->addColumn('placa', 'string', ['limit' => 10, 'null' => true, 'after' => 'modelo']);
        }

        // Adiciona índices únicos
        if (!$table->hasIndexByName('vehicles_placa_unique')) {
            $table->addIndex(['placa'], ['name' => 'vehicles_placa_unique', 'unique' => true]);
        }
        if (!$table->hasIndexByName('vehicles_chassi_unique')) {
            $table->addIndex(['chassi'], ['name' => 'vehicles_chassi_unique', 'unique' => true]);
        }
        if (!$table->hasIndexByName('vehicles_renavam_unique')) {
            $table->addIndex(['renavam'], ['name' => 'vehicles_renavam_unique', 'unique' => true]);
        }

        $table->update();

        // --- Drivers ---
        $drivers = $this->table('drivers');

        // Adiciona índice único para CPF se não existir
        if (!$drivers->hasIndexByName('drivers_cpf_unique')) {
            $drivers->addIndex(['cpf'], ['name' => 'drivers_cpf_unique', 'unique' => true]);
        }

        $drivers->update();
    }
}
