<?php

namespace Database\Seeders;

use Phinx\Seed\AbstractSeed;

class VehiclesSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'brand'                 => 'Fiat',
                'model'                 => 'Toro',
                'axles'                 => 2,
                'crlv'                  => 'CRLV-2026-001',
                'fuel_type'             => 'Diesel',
                'last_maintenance_date' => '2026-01-15',
                'next_maintenance_date' => '2026-07-15',
                'purchase_date'         => '2023-06-01',
                'photo1'                => null,
                'photo2'                => null,
                'photo3'                => null,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'brand'                 => 'Volkswagen',
                'model'                 => 'Constellation',
                'axles'                 => 3,
                'crlv'                  => 'CRLV-2026-002',
                'fuel_type'             => 'Diesel',
                'last_maintenance_date' => '2026-02-20',
                'next_maintenance_date' => '2026-08-20',
                'purchase_date'         => '2022-03-15',
                'photo1'                => null,
                'photo2'                => null,
                'photo3'                => null,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'brand'                 => 'Mercedes-Benz',
                'model'                 => 'Actros',
                'axles'                 => 3,
                'crlv'                  => 'CRLV-2026-003',
                'fuel_type'             => 'Diesel',
                'last_maintenance_date' => '2026-03-10',
                'next_maintenance_date' => '2026-09-10',
                'purchase_date'         => '2024-01-20',
                'photo1'                => null,
                'photo2'                => null,
                'photo3'                => null,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'brand'                 => 'Scania',
                'model'                 => 'R440',
                'axles'                 => 3,
                'crlv'                  => 'CRLV-2026-004',
                'fuel_type'             => 'Diesel',
                'last_maintenance_date' => '2026-04-05',
                'next_maintenance_date' => '2026-10-05',
                'purchase_date'         => '2021-08-10',
                'photo1'                => null,
                'photo2'                => null,
                'photo3'                => null,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'brand'                 => 'Ford',
                'model'                 => 'Transit',
                'axles'                 => 2,
                'crlv'                  => 'CRLV-2026-005',
                'fuel_type'             => 'Gasolina',
                'last_maintenance_date' => '2026-05-01',
                'next_maintenance_date' => '2026-11-01',
                'purchase_date'         => '2023-11-25',
                'photo1'                => null,
                'photo2'                => null,
                'photo3'                => null,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('vehicles')->insert($data)->saveData();
    }
}
