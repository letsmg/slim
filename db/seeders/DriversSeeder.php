<?php

namespace Database\Seeders;

use Phinx\Seed\AbstractSeed;

class DriversSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'name'         => 'Pedro Alves',
                'document'     => '123.456.789-00',
                'cnh'          => '12345678901',
                'phone'        => '(11) 99999-0001',
                'email'        => 'pedro@exemplo.com',
                'active'       => true,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Lucas Santos',
                'document'     => '987.654.321-00',
                'cnh'          => '98765432101',
                'phone'        => '(11) 99999-0002',
                'email'        => 'lucas@exemplo.com',
                'active'       => true,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Fernanda Lima',
                'document'     => '456.789.123-00',
                'cnh'          => '45678912301',
                'phone'        => '(11) 99999-0003',
                'email'        => 'fernanda@exemplo.com',
                'active'       => true,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Roberto Costa',
                'document'     => '321.654.987-00',
                'cnh'          => '32165498701',
                'phone'        => '(11) 99999-0004',
                'email'        => 'roberto@exemplo.com',
                'active'       => false,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'name'         => 'Juliana Martins',
                'document'     => '654.321.789-00',
                'cnh'          => '65432178901',
                'phone'        => '(11) 99999-0005',
                'email'        => 'juliana@exemplo.com',
                'active'       => true,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('drivers')->insert($data)->saveData();
    }
}
