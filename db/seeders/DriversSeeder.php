<?php

namespace Database\Seeders;

use Phinx\Seed\AbstractSeed;

class DriversSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'name'           => 'Pedro Alves',
                'cpf'            => '123.456.789-00',
                'rg'             => '12.345.678-9',
                'cnh'            => '12345678901',
                'cnh_category'   => 'D',
                'address'        => 'Rua das Flores, 123',
                'neighborhood'   => 'Centro',
                'city'           => 'Sao Paulo',
                'state'          => 'SP',
                'zipcode'        => '01001-000',
                'toxicological'  => true,
                'pending_issues' => false,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'Lucas Santos',
                'cpf'            => '987.654.321-00',
                'rg'             => '98.765.432-1',
                'cnh'            => '98765432101',
                'cnh_category'   => 'E',
                'address'        => 'Av. Paulista, 1000',
                'neighborhood'   => 'Bela Vista',
                'city'           => 'Sao Paulo',
                'state'          => 'SP',
                'zipcode'        => '01310-100',
                'toxicological'  => true,
                'pending_issues' => false,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'Fernanda Lima',
                'cpf'            => '456.789.123-00',
                'rg'             => '45.678.912-3',
                'cnh'            => '45678912301',
                'cnh_category'   => 'D',
                'address'        => 'Rua Augusta, 500',
                'neighborhood'   => 'Consolacao',
                'city'           => 'Sao Paulo',
                'state'          => 'SP',
                'zipcode'        => '01304-000',
                'toxicological'  => true,
                'pending_issues' => false,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'Roberto Costa',
                'cpf'            => '321.654.987-00',
                'rg'             => '32.165.498-7',
                'cnh'            => '32165498701',
                'cnh_category'   => 'C',
                'address'        => 'Rua da Consolacao, 200',
                'neighborhood'   => 'Consolacao',
                'city'           => 'Sao Paulo',
                'state'          => 'SP',
                'zipcode'        => '01302-000',
                'toxicological'  => false,
                'pending_issues' => true,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'Juliana Martins',
                'cpf'            => '654.321.789-00',
                'rg'             => '65.432.178-9',
                'cnh'            => '65432178901',
                'cnh_category'   => 'D',
                'address'        => 'Av. Brigadeiro, 300',
                'neighborhood'   => 'Jardins',
                'city'           => 'Sao Paulo',
                'state'          => 'SP',
                'zipcode'        => '01401-000',
                'toxicological'  => true,
                'pending_issues' => false,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('drivers')->insert($data)->saveData();
    }
}
