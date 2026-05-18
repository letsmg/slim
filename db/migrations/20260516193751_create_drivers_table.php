<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDriversTable extends AbstractMigration
{
    /**
     * Cria a tabela 'drivers' (motoristas)
     * Todos os campos em ingles conforme regra do projeto (.clinerules)
     */
    public function change(): void
    {
        $this->table('drivers')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('cpf', 'string', ['limit' => 14])
            ->addColumn('rg', 'string', ['limit' => 20])
            ->addColumn('cnh', 'string', ['limit' => 20])
            ->addColumn('cnh_category', 'string', ['limit' => 5])
            ->addColumn('address', 'string', ['limit' => 255])
            ->addColumn('neighborhood', 'string', ['limit' => 100])
            ->addColumn('city', 'string', ['limit' => 100])
            ->addColumn('state', 'string', ['limit' => 2])
            ->addColumn('zipcode', 'string', ['limit' => 10])
            ->addColumn('toxicological', 'boolean', ['default' => false])
            ->addColumn('pending_issues', 'boolean', ['default' => false])
            ->addColumn('photo', 'string', ['limit' => 255, 'null' => true])
            // Documentos fotograficos do motorista
            ->addColumn('cnh_photo', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('toxicological_photo', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('nr35_photo', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('nr20_photo', 'string', ['limit' => 255, 'null' => true])
            ->addIndex(['cpf'], ['name' => 'drivers_cpf_unique', 'unique' => true])
            ->addTimestamps()
            ->create();
    }
}
