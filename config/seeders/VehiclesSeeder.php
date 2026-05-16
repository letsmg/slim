<?php

namespace Database\Seeders;

use Phinx\Seed\AbstractSeed;

class VehiclesSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'marca'             => 'Fiat',
                'modelo'            => 'Toro',
                'eixos'             => 2,
                'crlv'              => 'CRLV-2026-001',
                'tipo_combustivel'  => 'Diesel',
                'dt_ultima_revisao' => '2026-01-15',
                'dt_proxima_revisao'=> '2026-07-15',
                'dt_compra'         => '2023-06-01',
                'photo1'            => null,
                'photo2'            => null,
                'photo3'            => null,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'marca'             => 'Volkswagen',
                'modelo'            => 'Constellation',
                'eixos'             => 3,
                'crlv'              => 'CRLV-2026-002',
                'tipo_combustivel'  => 'Diesel',
                'dt_ultima_revisao' => '2026-02-20',
                'dt_proxima_revisao'=> '2026-08-20',
                'dt_compra'         => '2022-03-15',
                'photo1'            => null,
                'photo2'            => null,
                'photo3'            => null,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'marca'             => 'Mercedes-Benz',
                'modelo'            => 'Actros',
                'eixos'             => 3,
                'crlv'              => 'CRLV-2026-003',
                'tipo_combustivel'  => 'Diesel',
                'dt_ultima_revisao' => '2026-03-10',
                'dt_proxima_revisao'=> '2026-09-10',
                'dt_compra'         => '2024-01-20',
                'photo1'            => null,
                'photo2'            => null,
                'photo3'            => null,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'marca'             => 'Scania',
                'modelo'            => 'R440',
                'eixos'             => 3,
                'crlv'              => 'CRLV-2026-004',
                'tipo_combustivel'  => 'Diesel',
                'dt_ultima_revisao' => '2026-04-05',
                'dt_proxima_revisao'=> '2026-10-05',
                'dt_compra'         => '2021-08-10',
                'photo1'            => null,
                'photo2'            => null,
                'photo3'            => null,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'marca'             => 'Ford',
                'modelo'            => 'Transit',
                'eixos'             => 2,
                'crlv'              => 'CRLV-2026-005',
                'tipo_combustivel'  => 'Gasolina',
                'dt_ultima_revisao' => '2026-05-01',
                'dt_proxima_revisao'=> '2026-11-01',
                'dt_compra'         => '2023-11-25',
                'photo1'            => null,
                'photo2'            => null,
                'photo3'            => null,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('vehicles')->insert($data)->saveData();
    }
}
