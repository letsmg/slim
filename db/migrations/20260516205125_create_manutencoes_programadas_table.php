<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateManutencoesProgramadasTable extends AbstractMigration
{
    /**
     * Cria a tabela 'scheduled_maintenances' (manutencoes programadas)
     * Campos em ingles conforme regra do projeto
     * Relaciona motorista, veiculo e mecanico
     */
    public function change(): void
    {
        $this->table('scheduled_maintenances')
            ->addColumn('driver_id', 'integer')
            ->addColumn('vehicle_id', 'integer')
            ->addColumn('mechanic_id', 'integer')
            ->addColumn('scheduled_date', 'date')           // data marcada
            ->addColumn('scheduled_time', 'time')           // horario
            ->addColumn('contact', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('service', 'text', ['null' => true])            // servico
            ->addColumn('observations', 'text', ['null' => true])       // observacoes
            ->addColumn('completed', 'boolean', ['default' => false])   // realizado
            ->addColumn('paid', 'boolean', ['default' => false])        // pago
            // 3 campos de fotos para manutenções
            ->addColumn('photo1', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('photo2', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('photo3', 'string', ['limit' => 255, 'null' => true])
            ->addForeignKey('driver_id', 'drivers', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('vehicle_id', 'vehicles', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('mechanic_id', 'mechanics', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addTimestamps()
            ->create();
    }
}
