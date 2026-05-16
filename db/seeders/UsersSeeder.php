<?php

namespace Database\Seeders;

use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'name'       => 'Admin',
                'email'      => 'admin@slimapp.com',
                'password'   => password_hash('123456', PASSWORD_ARGON2ID),
                'level'      => 'admin',
                'active'     => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'João Silva',
                'email'      => 'joao@slimapp.com',
                'password'   => password_hash('123456', PASSWORD_ARGON2ID),
                'level'      => 'operational',
                'active'     => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Maria Souza',
                'email'      => 'maria@slimapp.com',
                'password'   => password_hash('123456', PASSWORD_ARGON2ID),
                'level'      => 'operational',
                'active'     => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Carlos Pereira',
                'email'      => 'carlos@slimapp.com',
                'password'   => password_hash('123456', PASSWORD_ARGON2ID),
                'level'      => 'support',
                'active'     => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Ana Oliveira',
                'email'      => 'ana@slimapp.com',
                'password'   => password_hash('123456', PASSWORD_ARGON2ID),
                'level'      => 'support',
                'active'     => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('users')->insert($data)->saveData();
    }
}
