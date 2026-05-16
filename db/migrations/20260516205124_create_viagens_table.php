<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateViagensTable extends AbstractMigration
{
    /**
     * Cria a tabela 'trips' (viagens)
     * Campos em ingles conforme regra do projeto
     * Relaciona motorista e veiculo
     */
    public function change(): void
    {
        $this->table('trips')
            ->addColumn('driver_id', 'integer')
            ->addColumn('vehicle_id', 'integer')
            ->addColumn('departure_forecast', 'datetime')   // previsao de saida
            ->addColumn('arrival_forecast', 'datetime')     // previsao de chegada
            ->addColumn('origin', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('destination', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('status', 'string', ['limit' => 50, 'default' => 'scheduled']) // scheduled, in_progress, completed, cancelled
            ->addForeignKey('driver_id', 'drivers', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('vehicle_id', 'vehicles', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addTimestamps()
            ->create();
    }
}
