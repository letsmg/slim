<?php

namespace Database\Seeders;

use Phinx\Seed\AbstractSeed;

class MechanicsSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'name'       => 'Oficina do João',
                'address'    => 'Rua das Oficinas, 100',
                'document'   => '11.222.333/0001-01',
                'phone1'     => '(11) 98888-0001',
                'phone2'     => '(11) 97777-0001',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Mecânica do Zé',
                'address'    => 'Av. dos Mecânicos, 200',
                'document'   => '22.333.444/0001-02',
                'phone1'     => '(11) 98888-0002',
                'phone2'     => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Auto Center SP',
                'address'    => 'Rua das Peças, 300',
                'document'   => '33.444.555/0001-03',
                'phone1'     => '(11) 98888-0003',
                'phone2'     => '(11) 97777-0003',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Oficina do Paulo',
                'address'    => 'Av. das Reparações, 400',
                'document'   => '44.555.666/0001-04',
                'phone1'     => '(11) 98888-0004',
                'phone2'     => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Mecânica Express',
                'address'    => 'Rua Rápida, 500',
                'document'   => '55.666.777/0001-05',
                'phone1'     => '(11) 98888-0005',
                'phone2'     => '(11) 97777-0005',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('mechanics')->insert($data)->saveData();
    }
}
