<?php

namespace Database\Seeders;

use Phinx\Seed\AbstractSeed;

class TripsSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'driver_id'          => 1,
                'vehicle_id'         => 1,
                'departure_forecast' => date('Y-m-d H:i:s', strtotime('+1 day')),
                'arrival_forecast'   => date('Y-m-d H:i:s', strtotime('+3 days')),
                'origin'             => 'São Paulo, SP',
                'destination'        => 'Rio de Janeiro, RJ',
                'status'             => 'scheduled',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'driver_id'          => 2,
                'vehicle_id'         => 2,
                'departure_forecast' => date('Y-m-d H:i:s', strtotime('+2 days')),
                'arrival_forecast'   => date('Y-m-d H:i:s', strtotime('+5 days')),
                'origin'             => 'Belo Horizonte, MG',
                'destination'        => 'Brasília, DF',
                'status'             => 'scheduled',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'driver_id'          => 3,
                'vehicle_id'         => 3,
                'departure_forecast' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'arrival_forecast'   => date('Y-m-d H:i:s', strtotime('+1 day')),
                'origin'             => 'Curitiba, PR',
                'destination'        => 'Porto Alegre, RS',
                'status'             => 'in_progress',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'driver_id'          => 1,
                'vehicle_id'         => 5,
                'departure_forecast' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'arrival_forecast'   => date('Y-m-d H:i:s', strtotime('-3 days')),
                'origin'             => 'São Paulo, SP',
                'destination'        => 'Campinas, SP',
                'status'             => 'completed',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'driver_id'          => 4,
                'vehicle_id'         => 4,
                'departure_forecast' => date('Y-m-d H:i:s', strtotime('+5 days')),
                'arrival_forecast'   => date('Y-m-d H:i:s', strtotime('+7 days')),
                'origin'             => 'Salvador, BA',
                'destination'        => 'Recife, PE',
                'status'             => 'cancelled',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('trips')->insert($data)->saveData();
    }
}
