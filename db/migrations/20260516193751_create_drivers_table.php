<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDriversTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('drivers')
            ->addColumn('nome', 'string', ['limit' => 255])
            ->addColumn('cpf', 'string', ['limit' => 14])
            ->addColumn('rg', 'string', ['limit' => 20])
            ->addColumn('cnh', 'string', ['limit' => 20])
            ->addColumn('categoria_cnh', 'string', ['limit' => 5])
            ->addColumn('endereco', 'string', ['limit' => 255])
            ->addColumn('bairro', 'string', ['limit' => 100])
            ->addColumn('cidade', 'string', ['limit' => 100])
            ->addColumn('estado', 'string', ['limit' => 2])
            ->addColumn('cep', 'string', ['limit' => 10])
            ->addColumn('toxicologico', 'boolean', ['default' => false])
            ->addColumn('pendencias', 'boolean', ['default' => false])
            ->addTimestamps()
            ->create();
    }
}
