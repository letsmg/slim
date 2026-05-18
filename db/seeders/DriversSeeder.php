<?php

namespace Database\Seeders;

use Phinx\Seed\AbstractSeed;

class DriversSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'nome'           => 'Pedro Alves',
                'cpf'            => '123.456.789-00',
                'rg'             => '12.345.678-9',
                'cnh'            => '12345678901',
                'categoria_cnh'  => 'D',
                'endereco'       => 'Rua das Flores, 123',
                'bairro'         => 'Centro',
                'cidade'         => 'São Paulo',
                'estado'         => 'SP',
                'cep'            => '01001-000',
                'toxicologico'   => true,
                'pendencias'     => false,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'           => 'Lucas Santos',
                'cpf'            => '987.654.321-00',
                'rg'             => '98.765.432-1',
                'cnh'            => '98765432101',
                'categoria_cnh'  => 'E',
                'endereco'       => 'Av. Paulista, 1000',
                'bairro'         => 'Bela Vista',
                'cidade'         => 'São Paulo',
                'estado'         => 'SP',
                'cep'            => '01310-100',
                'toxicologico'   => true,
                'pendencias'     => false,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'           => 'Fernanda Lima',
                'cpf'            => '456.789.123-00',
                'rg'             => '45.678.912-3',
                'cnh'            => '45678912301',
                'categoria_cnh'  => 'D',
                'endereco'       => 'Rua Augusta, 500',
                'bairro'         => 'Consolação',
                'cidade'         => 'São Paulo',
                'estado'         => 'SP',
                'cep'            => '01304-000',
                'toxicologico'   => true,
                'pendencias'     => false,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'           => 'Roberto Costa',
                'cpf'            => '321.654.987-00',
                'rg'             => '32.165.498-7',
                'cnh'            => '32165498701',
                'categoria_cnh'  => 'C',
                'endereco'       => 'Rua da Consolação, 200',
                'bairro'         => 'Consolação',
                'cidade'         => 'São Paulo',
                'estado'         => 'SP',
                'cep'            => '01302-000',
                'toxicologico'   => false,
                'pendencias'     => true,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'           => 'Juliana Martins',
                'cpf'            => '654.321.789-00',
                'rg'             => '65.432.178-9',
                'cnh'            => '65432178901',
                'categoria_cnh'  => 'D',
                'endereco'       => 'Av. Brigadeiro, 300',
                'bairro'         => 'Jardins',
                'cidade'         => 'São Paulo',
                'estado'         => 'SP',
                'cep'            => '01401-000',
                'toxicologico'   => true,
                'pendencias'     => false,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('drivers')->insert($data)->saveData();
    }
}
