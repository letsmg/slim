<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateVehiclesTable extends AbstractMigration
{
    /**
     * Cria a tabela 'vehicles' (veiculos)
     * Todos os campos em ingles conforme regra do projeto (.clinerules)
     */
    public function change(): void
    {
        $this->table('vehicles')
            ->addColumn('brand', 'string', ['limit' => 100])
            ->addColumn('model', 'string', ['limit' => 100])
            ->addColumn('plate', 'string', ['limit' => 10, 'null' => true])
            ->addColumn('axles', 'integer', ['default' => 2])
            ->addColumn('crlv', 'string', ['limit' => 50])
            ->addColumn('chassis', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('renavam', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('fuel_type', 'string', ['limit' => 50])
            ->addColumn('last_maintenance_date', 'date', ['null' => true])
            ->addColumn('next_maintenance_date', 'date', ['null' => true])
            ->addColumn('purchase_date', 'date', ['null' => true])
            // Fotos dos veiculos (multiplas imagens)
            ->addColumn('photo1', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('photo2', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('photo3', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('photo4', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('photo5', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('photo6', 'string', ['limit' => 255, 'null' => true])
            // Foto ANTT
            ->addColumn('antt_photo', 'string', ['limit' => 255, 'null' => true])
            ->addIndex(['plate'], ['name' => 'vehicles_plate_unique', 'unique' => true])
            ->addIndex(['chassis'], ['name' => 'vehicles_chassis_unique', 'unique' => true])
            ->addIndex(['renavam'], ['name' => 'vehicles_renavam_unique', 'unique' => true])
            ->addTimestamps()
            ->create();
    }
}
