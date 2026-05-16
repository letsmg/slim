<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMechanicsTable extends AbstractMigration
{
    /**
     * Cria a tabela 'mechanics' (mecânicos)
     * Campos em ingles conforme regra do projeto
     */
    public function change(): void
    {
        $this->table('mechanics')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('address', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('document', 'string', ['limit' => 20]) // CPF/CNPJ
            ->addColumn('phone1', 'string', ['limit' => 20])
            ->addColumn('phone2', 'string', ['limit' => 20, 'null' => true])
            ->addTimestamps()
            ->create();
    }
}
