<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    /**
     * Cria a tabela users com níveis de acesso
     * Níveis: admin (administrador), operational (operacional), support (suporte)
     * Senhas armazenadas com hash Argon2id
     */
    public function change(): void
    {
        $this->table('users')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('email', 'string', ['limit' => 255])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('level', 'string', ['limit' => 20, 'default' => 'operational'])
            ->addColumn('active', 'boolean', ['default' => true])
            ->addTimestamps()
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
