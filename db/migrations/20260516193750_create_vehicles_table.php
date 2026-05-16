<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateVehiclesTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('vehicles')
            ->addColumn('marca', 'string', ['limit' => 100])
            ->addColumn('modelo', 'string', ['limit' => 100])
            ->addColumn('eixos', 'integer', ['default' => 2])
            ->addColumn('crlv', 'string', ['limit' => 50])
            ->addColumn('tipo_combustivel', 'string', ['limit' => 50])
            ->addColumn('dt_ultima_revisao', 'date', ['null' => true])
            ->addColumn('dt_proxima_revisao', 'date', ['null' => true])
            ->addColumn('dt_compra', 'date', ['null' => true])
            // 3 campos de fotos para veículos
            ->addColumn('photo1', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('photo2', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('photo3', 'string', ['limit' => 255, 'null' => true])
            ->addTimestamps()
            ->create();
    }
}
