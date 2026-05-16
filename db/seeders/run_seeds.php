<?php
/**
 * Script para rodar os seeders manualmente
 * Phinx não suporta namespaces corretamente, então usamos o Eloquent diretamente
 */

require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$config = require __DIR__ . '/../../config/config.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => $config['database']['connection'],
    'host'      => $config['database']['host'],
    'port'      => $config['database']['port'],
    'database'  => $config['database']['database'],
    'username'  => $config['database']['username'],
    'password'  => $config['database']['password'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== Iniciando seeders ===\n\n";

// Limpa tabelas na ordem correta (respeitando chaves estrangeiras)
Capsule::statement("TRUNCATE TABLE scheduled_maintenances RESTART IDENTITY CASCADE");
Capsule::statement("TRUNCATE TABLE trips RESTART IDENTITY CASCADE");
Capsule::statement("TRUNCATE TABLE mechanics RESTART IDENTITY CASCADE");
Capsule::statement("TRUNCATE TABLE drivers RESTART IDENTITY CASCADE");
Capsule::statement("TRUNCATE TABLE vehicles RESTART IDENTITY CASCADE");
Capsule::statement("TRUNCATE TABLE users RESTART IDENTITY CASCADE");
echo "Tabelas limpas.\n\n";

// Users
$users = [
    ['name' => 'Admin', 'email' => 'admin@slimapp.com', 'password' => password_hash('123456', PASSWORD_ARGON2ID), 'level' => 'admin', 'active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['name' => 'João Silva', 'email' => 'joao@slimapp.com', 'password' => password_hash('123456', PASSWORD_ARGON2ID), 'level' => 'operational', 'active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['name' => 'Maria Souza', 'email' => 'maria@slimapp.com', 'password' => password_hash('123456', PASSWORD_ARGON2ID), 'level' => 'operational', 'active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['name' => 'Carlos Pereira', 'email' => 'carlos@slimapp.com', 'password' => password_hash('123456', PASSWORD_ARGON2ID), 'level' => 'support', 'active' => false, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['name' => 'Ana Oliveira', 'email' => 'ana@slimapp.com', 'password' => password_hash('123456', PASSWORD_ARGON2ID), 'level' => 'support', 'active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
];
Capsule::table('users')->insert($users);
echo "Users: " . count($users) . " registros inseridos.\n";

// Vehicles
$vehicles = [
    ['marca' => 'Fiat', 'modelo' => 'Toro', 'eixos' => 2, 'crlv' => 'CRLV-2026-001', 'tipo_combustivel' => 'Diesel', 'dt_ultima_revisao' => '2026-01-15', 'dt_proxima_revisao' => '2026-07-15', 'dt_compra' => '2023-06-01', 'photo1' => null, 'photo2' => null, 'photo3' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['marca' => 'Volkswagen', 'modelo' => 'Constellation', 'eixos' => 3, 'crlv' => 'CRLV-2026-002', 'tipo_combustivel' => 'Diesel', 'dt_ultima_revisao' => '2026-02-20', 'dt_proxima_revisao' => '2026-08-20', 'dt_compra' => '2022-03-15', 'photo1' => null, 'photo2' => null, 'photo3' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['marca' => 'Mercedes-Benz', 'modelo' => 'Actros', 'eixos' => 3, 'crlv' => 'CRLV-2026-003', 'tipo_combustivel' => 'Diesel', 'dt_ultima_revisao' => '2026-03-10', 'dt_proxima_revisao' => '2026-09-10', 'dt_compra' => '2024-01-20', 'photo1' => null, 'photo2' => null, 'photo3' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['marca' => 'Scania', 'modelo' => 'R440', 'eixos' => 3, 'crlv' => 'CRLV-2026-004', 'tipo_combustivel' => 'Diesel', 'dt_ultima_revisao' => '2026-04-05', 'dt_proxima_revisao' => '2026-10-05', 'dt_compra' => '2021-08-10', 'photo1' => null, 'photo2' => null, 'photo3' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['marca' => 'Ford', 'modelo' => 'Transit', 'eixos' => 2, 'crlv' => 'CRLV-2026-005', 'tipo_combustivel' => 'Gasolina', 'dt_ultima_revisao' => '2026-05-01', 'dt_proxima_revisao' => '2026-11-01', 'dt_compra' => '2023-11-25', 'photo1' => null, 'photo2' => null, 'photo3' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
];
Capsule::table('vehicles')->insert($vehicles);
echo "Vehicles: " . count($vehicles) . " registros inseridos.\n";

// Drivers (campos em portugues conforme migration)
$drivers = [
    ['nome' => 'Pedro Alves', 'cpf' => '123.456.789-00', 'rg' => '12.345.678-9', 'cnh' => '12345678901', 'categoria_cnh' => 'E', 'endereco' => 'Rua A, 123', 'bairro' => 'Centro', 'cidade' => 'São Paulo', 'estado' => 'SP', 'cep' => '01001-000', 'toxicologico' => true, 'pendencias' => false, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['nome' => 'Lucas Santos', 'cpf' => '987.654.321-00', 'rg' => '98.765.432-1', 'cnh' => '98765432101', 'categoria_cnh' => 'D', 'endereco' => 'Rua B, 456', 'bairro' => 'Jardins', 'cidade' => 'São Paulo', 'estado' => 'SP', 'cep' => '01401-000', 'toxicologico' => true, 'pendencias' => false, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['nome' => 'Fernanda Lima', 'cpf' => '456.789.123-00', 'rg' => '45.678.912-3', 'cnh' => '45678912301', 'categoria_cnh' => 'E', 'endereco' => 'Rua C, 789', 'bairro' => 'Vila Nova', 'cidade' => 'Campinas', 'estado' => 'SP', 'cep' => '13001-000', 'toxicologico' => true, 'pendencias' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['nome' => 'Roberto Costa', 'cpf' => '321.654.987-00', 'rg' => '32.165.498-7', 'cnh' => '32165498701', 'categoria_cnh' => 'D', 'endereco' => 'Rua D, 321', 'bairro' => 'Industrial', 'cidade' => 'Belo Horizonte', 'estado' => 'MG', 'cep' => '30001-000', 'toxicologico' => false, 'pendencias' => false, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['nome' => 'Juliana Martins', 'cpf' => '654.321.789-00', 'rg' => '65.432.178-9', 'cnh' => '65432178901', 'categoria_cnh' => 'E', 'endereco' => 'Rua E, 654', 'bairro' => 'Centro', 'cidade' => 'Curitiba', 'estado' => 'PR', 'cep' => '80001-000', 'toxicologico' => true, 'pendencias' => false, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
];
Capsule::table('drivers')->insert($drivers);
echo "Drivers: " . count($drivers) . " registros inseridos.\n";

// Mechanics
$mechanics = [
    ['name' => 'Oficina do João', 'address' => 'Rua A, 123', 'document' => '11.222.333/0001-01', 'phone1' => '(11) 97777-0001', 'phone2' => '(11) 97777-0001', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['name' => 'Auto Mecânica Silva', 'address' => 'Rua B, 456', 'document' => '22.333.444/0001-02', 'phone1' => '(11) 97777-0002', 'phone2' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['name' => 'Mecânica do Zé', 'address' => 'Rua C, 789', 'document' => '33.444.555/0001-03', 'phone1' => '(11) 97777-0003', 'phone2' => '(11) 97777-0003', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['name' => 'Oficina Center', 'address' => 'Rua D, 321', 'document' => '44.555.666/0001-04', 'phone1' => '(11) 97777-0004', 'phone2' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['name' => 'Mecânica Express', 'address' => 'Rua E, 654', 'document' => '55.666.777/0001-05', 'phone1' => '(11) 97777-0005', 'phone2' => '(11) 97777-0005', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
];
Capsule::table('mechanics')->insert($mechanics);
echo "Mechanics: " . count($mechanics) . " registros inseridos.\n";

// Trips
$trips = [
    ['driver_id' => 1, 'vehicle_id' => 1, 'departure_forecast' => date('Y-m-d H:i:s', strtotime('+1 day')), 'arrival_forecast' => date('Y-m-d H:i:s', strtotime('+3 days')), 'origin' => 'São Paulo', 'destination' => 'Rio de Janeiro', 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['driver_id' => 2, 'vehicle_id' => 2, 'departure_forecast' => date('Y-m-d H:i:s', strtotime('+2 days')), 'arrival_forecast' => date('Y-m-d H:i:s', strtotime('+5 days')), 'origin' => 'Belo Horizonte', 'destination' => 'Brasília', 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['driver_id' => 3, 'vehicle_id' => 3, 'departure_forecast' => date('Y-m-d H:i:s', strtotime('-2 days')), 'arrival_forecast' => date('Y-m-d H:i:s', strtotime('+1 day')), 'origin' => 'Curitiba', 'destination' => 'Porto Alegre', 'status' => 'in_progress', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['driver_id' => 1, 'vehicle_id' => 5, 'departure_forecast' => date('Y-m-d H:i:s', strtotime('-5 days')), 'arrival_forecast' => date('Y-m-d H:i:s', strtotime('-3 days')), 'origin' => 'São Paulo', 'destination' => 'Campinas', 'status' => 'completed', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['driver_id' => 5, 'vehicle_id' => 1, 'departure_forecast' => date('Y-m-d H:i:s', strtotime('+5 days')), 'arrival_forecast' => date('Y-m-d H:i:s', strtotime('+8 days')), 'origin' => 'Rio de Janeiro', 'destination' => 'Salvador', 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
];
Capsule::table('trips')->insert($trips);
echo "Trips: " . count($trips) . " registros inseridos.\n";

// Scheduled Maintenances
$maintenances = [
    ['driver_id' => 1, 'vehicle_id' => 1, 'mechanic_id' => 1, 'scheduled_date' => date('Y-m-d', strtotime('+7 days')), 'scheduled_time' => '08:00:00', 'contact' => '(11) 98888-0001', 'service' => 'Troca de óleo e filtros', 'observations' => 'Utilizar óleo sintético 5W30', 'completed' => false, 'paid' => false, 'photo1' => null, 'photo2' => null, 'photo3' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['driver_id' => 2, 'vehicle_id' => 2, 'mechanic_id' => 2, 'scheduled_date' => date('Y-m-d', strtotime('+14 days')), 'scheduled_time' => '10:00:00', 'contact' => '(11) 98888-0002', 'service' => 'Revisão geral dos freios', 'observations' => 'Verificar pastilhas e discos', 'completed' => false, 'paid' => false, 'photo1' => null, 'photo2' => null, 'photo3' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['driver_id' => 3, 'vehicle_id' => 3, 'mechanic_id' => 3, 'scheduled_date' => date('Y-m-d', strtotime('-3 days')), 'scheduled_time' => '14:00:00', 'contact' => '(11) 98888-0003', 'service' => 'Alinhamento e balanceamento', 'observations' => 'Verificar geometria da suspensão', 'completed' => true, 'paid' => true, 'photo1' => null, 'photo2' => null, 'photo3' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['driver_id' => 1, 'vehicle_id' => 5, 'mechanic_id' => 4, 'scheduled_date' => date('Y-m-d', strtotime('-10 days')), 'scheduled_time' => '09:00:00', 'contact' => '(11) 98888-0004', 'service' => 'Troca de pneus', 'observations' => '4 pneus novos, medida 205/55R16', 'completed' => true, 'paid' => false, 'photo1' => null, 'photo2' => null, 'photo3' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ['driver_id' => 5, 'vehicle_id' => 1, 'mechanic_id' => 5, 'scheduled_date' => date('Y-m-d', strtotime('+21 days')), 'scheduled_time' => '16:00:00', 'contact' => '(11) 98888-0005', 'service' => 'Revisão completa 50.000 km', 'observations' => 'Incluir troca de correia dentada', 'completed' => false, 'paid' => false, 'photo1' => null, 'photo2' => null, 'photo3' => null, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
];
Capsule::table('scheduled_maintenances')->insert($maintenances);
echo "Scheduled Maintenances: " . count($maintenances) . " registros inseridos.\n";

echo "\n=== Todos os seeders executados com sucesso! ===\n";
