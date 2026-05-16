<?php

namespace Database\Seeders;

use Phinx\Seed\AbstractSeed;

class ScheduledMaintenancesSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'driver_id'        => 1,
                'vehicle_id'       => 1,
                'mechanic_id'      => 1,
                'scheduled_date'   => date('Y-m-d', strtotime('+7 days')),
                'scheduled_time'   => '08:00:00',
                'contact'          => '(11) 98888-0001',
                'service'          => 'Troca de óleo e filtros',
                'observations'     => 'Utilizar óleo sintético 5W30',
                'completed'        => false,
                'paid'             => false,
                'photo1'           => null,
                'photo2'           => null,
                'photo3'           => null,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'driver_id'        => 2,
                'vehicle_id'       => 2,
                'mechanic_id'      => 2,
                'scheduled_date'   => date('Y-m-d', strtotime('+14 days')),
                'scheduled_time'   => '10:00:00',
                'contact'          => '(11) 98888-0002',
                'service'          => 'Revisão geral dos freios',
                'observations'     => 'Verificar pastilhas e discos',
                'completed'        => false,
                'paid'             => false,
                'photo1'           => null,
                'photo2'           => null,
                'photo3'           => null,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'driver_id'        => 3,
                'vehicle_id'       => 3,
                'mechanic_id'      => 3,
                'scheduled_date'   => date('Y-m-d', strtotime('-3 days')),
                'scheduled_time'   => '14:00:00',
                'contact'          => '(11) 98888-0003',
                'service'          => 'Alinhamento e balanceamento',
                'observations'     => 'Verificar geometria da suspensão',
                'completed'        => true,
                'paid'             => true,
                'photo1'           => null,
                'photo2'           => null,
                'photo3'           => null,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'driver_id'        => 1,
                'vehicle_id'       => 5,
                'mechanic_id'      => 4,
                'scheduled_date'   => date('Y-m-d', strtotime('-10 days')),
                'scheduled_time'   => '09:00:00',
                'contact'          => '(11) 98888-0004',
                'service'          => 'Troca de pneus',
                'observations'     => '4 pneus novos, medida 205/55R16',
                'completed'        => true,
                'paid'             => false,
                'photo1'           => null,
                'photo2'           => null,
                'photo3'           => null,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'driver_id'        => 5,
                'vehicle_id'       => 1,
                'mechanic_id'      => 5,
                'scheduled_date'   => date('Y-m-d', strtotime('+21 days')),
                'scheduled_time'   => '16:00:00',
                'contact'          => '(11) 98888-0005',
                'service'          => 'Revisão completa 50.000 km',
                'observations'     => 'Incluir troca de correia dentada',
                'completed'        => false,
                'paid'             => false,
                'photo1'           => null,
                'photo2'           => null,
                'photo3'           => null,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('scheduled_maintenances')->insert($data)->saveData();
    }
}
