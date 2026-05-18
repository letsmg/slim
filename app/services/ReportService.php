<?php

namespace App\Services;

use App\Repositories\ReportRepository;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Service de Report - Camada de logica para relatorios
 * Orquestra a geracao de relatorios a partir dos repositorios
 * Gera PDFs com DomPDF usando logo e nome do sistema
 */
class ReportService
{
    private string $appName = 'Slim App';
    private string $logoPath;

    public function __construct(
        private ReportRepository $repository
    ) {
        // Caminho absoluto da logo para o DomPDF
        $this->logoPath = realpath(__DIR__ . '/../../public/imgs/logo2.png') ?: '';
    }

    /**
     * Lista todos os tipos de relatorio disponiveis
     * 
     * @return array
     */
    public function list(): array
    {
        return [
            [
                'id'          => 'general',
                'name'        => 'Relatorio Geral',
                'description' => 'Estatisticas gerais do sistema (usuarios, veiculos, viagens e manutencoes)',
            ],
            [
                'id'          => 'users',
                'name'        => 'Relatorio de Usuarios',
                'description' => 'Detalhamento de usuarios cadastrados e status',
            ],
            [
                'id'          => 'vehicles',
                'name'        => 'Relatorio de Veiculos',
                'description' => 'Detalhamento de veiculos cadastrados',
            ],
            [
                'id'          => 'trips',
                'name'        => 'Relatorio de Viagens',
                'description' => 'Detalhamento de viagens por status',
            ],
            [
                'id'          => 'maintenances',
                'name'        => 'Relatorio de Manutencoes',
                'description' => 'Detalhamento de manutencoes programadas',
            ],
        ];
    }

    /**
     * Gera um relatorio baseado no tipo
     * 
     * @param string $type Tipo do relatorio (general, users, vehicles, trips, maintenances)
     * @return array|null
     */
    public function generate(string $type): ?array
    {
        return match ($type) {
            'general'      => $this->repository->getGeneralStats(),
            'users'        => $this->repository->getUserReport(),
            'vehicles'     => $this->repository->getVehicleReport(),
            'trips'        => $this->repository->getTripReport(),
            'maintenances' => $this->repository->getMaintenanceReport(),
            default        => null,
        };
    }

    /**
     * Gera um PDF do relatorio e retorna o conteudo binario
     * 
     * @param string $type Tipo do relatorio
     * @return string|null Conteudo binario do PDF ou null se tipo invalido
     */
    public function generatePdf(string $type): ?string
    {
        $data = $this->generate($type);
        if ($data === null) {
            return null;
        }

        $html = $this->buildHtml($type, $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Gera um CSV do relatorio para importacao no Power BI
     * 
     * @param string $type Tipo do relatorio
     * @return string|null Conteudo do CSV ou null se tipo invalido
     */
    public function generateCsv(string $type): ?string
    {
        $data = $this->generate($type);
        if ($data === null) {
            return null;
        }

        return match ($type) {
            'general'      => $this->buildGeneralCsv($data),
            'users'        => $this->buildUserCsv($data),
            'vehicles'     => $this->buildVehicleCsv($data),
            'trips'        => $this->buildTripCsv($data),
            'maintenances' => $this->buildMaintenanceCsv($data),
            default        => null,
        };
    }

    /**
     * Escapa um valor para CSV (coloca entre aspas se necessario)
     */
    private function csvEscape(mixed $value): string
    {
        $value = (string) ($value ?? '');
        // Se contiver aspas, virgula, quebra de linha, envolve em aspas duplas
        if (str_contains($value, '"') || str_contains($value, ',') || str_contains($value, "\n") || str_contains($value, "\r")) {
            $value = '"' . str_replace('"', '""', $value) . '"';
        }
        return $value;
    }

    /**
     * Monta CSV do relatorio geral (estatisticas)
     */
    private function buildGeneralCsv(array $data): string
    {
        $u = $data['users'] ?? [];
        $v = $data['vehicles'] ?? [];
        $t = $data['trips'] ?? [];
        $m = $data['maintenances'] ?? [];

        $csv = "categoria,metrica,valor\n";
        $csv .= "Usuarios,Total,{$this->csvEscape($u['total'] ?? 0)}\n";
        $csv .= "Usuarios,Ativos,{$this->csvEscape($u['active'] ?? 0)}\n";
        $csv .= "Usuarios,Bloqueados,{$this->csvEscape($u['blocked'] ?? 0)}\n";
        $csv .= "Veiculos,Total,{$this->csvEscape($v['total'] ?? 0)}\n";
        $csv .= "Veiculos,Ativos,{$this->csvEscape($v['active'] ?? 0)}\n";
        $csv .= "Viagens,Total,{$this->csvEscape($t['total'] ?? 0)}\n";
        $csv .= "Viagens,Pendentes,{$this->csvEscape($t['pending'] ?? 0)}\n";
        $csv .= "Viagens,Em Andamento,{$this->csvEscape($t['in_progress'] ?? 0)}\n";
        $csv .= "Viagens,Concluidas,{$this->csvEscape($t['completed'] ?? 0)}\n";
        $csv .= "Viagens,No Mes,{$this->csvEscape($t['this_month'] ?? 0)}\n";
        $csv .= "Manutencoes,Total,{$this->csvEscape($m['total'] ?? 0)}\n";
        $csv .= "Manutencoes,Pendentes,{$this->csvEscape($m['pending'] ?? 0)}\n";
        $csv .= "Manutencoes,Concluidas,{$this->csvEscape($m['completed'] ?? 0)}\n";
        $csv .= "Manutencoes,No Mes,{$this->csvEscape($m['this_month'] ?? 0)}\n";
        return $csv;
    }

    /**
     * Monta CSV do relatorio de usuarios
     */
    private function buildUserCsv(array $data): string
    {
        $users = $data['recent_users'] ?? [];

        $csv = "id,nome,email,nivel,ativo,criado_em,atualizado_em\n";
        foreach ($users as $u) {
            $csv .= implode(',', [
                $this->csvEscape($u['id'] ?? ''),
                $this->csvEscape($u['name'] ?? ''),
                $this->csvEscape($u['email'] ?? ''),
                $this->csvEscape($u['level'] ?? ''),
                $this->csvEscape($u['active'] ?? ''),
                $this->csvEscape($u['created_at'] ?? ''),
                $this->csvEscape($u['updated_at'] ?? ''),
            ]) . "\n";
        }
        return $csv;
    }

    /**
     * Monta CSV do relatorio de veiculos
     */
    private function buildVehicleCsv(array $data): string
    {
        $vehicles = $data['recent_vehicles'] ?? [];

        $csv = "id,marca,modelo,placa,eixos,crlv,chassi,renavam,combustivel,ultima_revisao,proxima_revisao,data_compra,criado_em,atualizado_em\n";
        foreach ($vehicles as $v) {
            $csv .= implode(',', [
                $this->csvEscape($v['id'] ?? ''),
                $this->csvEscape($v['brand'] ?? ''),
                $this->csvEscape($v['model'] ?? ''),
                $this->csvEscape($v['plate'] ?? ''),
                $this->csvEscape($v['axles'] ?? ''),
                $this->csvEscape($v['crlv'] ?? ''),
                $this->csvEscape($v['chassis'] ?? ''),
                $this->csvEscape($v['renavam'] ?? ''),
                $this->csvEscape($v['fuel_type'] ?? ''),
                $this->csvEscape($v['last_maintenance_date'] ?? ''),
                $this->csvEscape($v['next_maintenance_date'] ?? ''),
                $this->csvEscape($v['purchase_date'] ?? ''),
                $this->csvEscape($v['created_at'] ?? ''),
                $this->csvEscape($v['updated_at'] ?? ''),
            ]) . "\n";
        }
        return $csv;
    }

    /**
     * Monta CSV do relatorio de viagens
     */
    private function buildTripCsv(array $data): string
    {
        $trips = $data['recent_trips'] ?? [];

        $csv = "id,origem,destino,status,motorista_id,veiculo_id,previsao_saida,previsao_chegada,saida_real,chegada_real,observacoes,criado_em,atualizado_em\n";
        foreach ($trips as $t) {
            $status = match ($t['status'] ?? '') {
                'pending'     => 'Pendente',
                'in_progress' => 'Em Andamento',
                'completed'   => 'Concluida',
                default       => $t['status'] ?? '',
            };
            $csv .= implode(',', [
                $this->csvEscape($t['id'] ?? ''),
                $this->csvEscape($t['origin'] ?? ''),
                $this->csvEscape($t['destination'] ?? ''),
                $this->csvEscape($status),
                $this->csvEscape($t['driver_id'] ?? ''),
                $this->csvEscape($t['vehicle_id'] ?? ''),
                $this->csvEscape($t['departure_forecast'] ?? ''),
                $this->csvEscape($t['arrival_forecast'] ?? ''),
                $this->csvEscape($t['departure_real'] ?? ''),
                $this->csvEscape($t['arrival_real'] ?? ''),
                $this->csvEscape($t['observations'] ?? ''),
                $this->csvEscape($t['created_at'] ?? ''),
                $this->csvEscape($t['updated_at'] ?? ''),
            ]) . "\n";
        }
        return $csv;
    }

    /**
     * Monta CSV do relatorio de manutencoes
     */
    private function buildMaintenanceCsv(array $data): string
    {
        $maintenances = $data['recent_maintenances'] ?? [];

        $csv = "id,servico,data_programada,concluida,observacoes,veiculo_id,mecanico_id,motorista_id,criado_em,atualizado_em\n";
        foreach ($maintenances as $m) {
            $completed = !empty($m['completed']) ? 'Sim' : 'Nao';
            $csv .= implode(',', [
                $this->csvEscape($m['id'] ?? ''),
                $this->csvEscape($m['service'] ?? ''),
                $this->csvEscape($m['scheduled_date'] ?? ''),
                $this->csvEscape($completed),
                $this->csvEscape($m['observations'] ?? ''),
                $this->csvEscape($m['vehicle_id'] ?? ''),
                $this->csvEscape($m['mechanic_id'] ?? ''),
                $this->csvEscape($m['driver_id'] ?? ''),
                $this->csvEscape($m['created_at'] ?? ''),
                $this->csvEscape($m['updated_at'] ?? ''),
            ]) . "\n";
        }
        return $csv;
    }

    /**
     * Monta o HTML do relatorio com cabecalho padronizado (logo + nome do sistema)
     */
    private function buildHtml(string $type, array $data): string
    {
        $title = match ($type) {
            'general'      => 'Relatorio Geral',
            'users'        => 'Relatorio de Usuarios',
            'vehicles'     => 'Relatorio de Veiculos',
            'trips'        => 'Relatorio de Viagens',
            'maintenances' => 'Relatorio de Manutencoes',
            default        => 'Relatorio',
        };

        $generatedAt = $data['generated_at'] ?? date('Y-m-d H:i:s');

        // Logo inline em base64 para funcionar no DomPDF
        $logoBase64 = '';
        if ($this->logoPath && file_exists($this->logoPath)) {
            $logoData = file_get_contents($this->logoPath);
            if ($logoData !== false) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
            }
        }

        $rows = '';
        switch ($type) {
            case 'general':
                $rows = $this->buildGeneralRows($data);
                break;
            case 'users':
                $rows = $this->buildUserRows($data);
                break;
            case 'vehicles':
                $rows = $this->buildVehicleRows($data);
                break;
            case 'trips':
                $rows = $this->buildTripRows($data);
                break;
            case 'maintenances':
                $rows = $this->buildMaintenanceRows($data);
                break;
        }

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 20mm 15mm; }
    body { font-family: Helvetica, sans-serif; font-size: 12px; color: #1f2937; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #059669; padding-bottom: 15px; }
    .header img { max-height: 50px; margin-bottom: 5px; }
    .header h1 { font-size: 18px; color: #059669; margin: 5px 0 0 0; }
    .header .subtitle { font-size: 11px; color: #6b7280; margin-top: 3px; }
    .title { font-size: 16px; color: #1f2937; margin: 15px 0 10px 0; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th { background-color: #059669; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; }
    td { padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
    tr:nth-child(even) td { background-color: #f9fafb; }
    .footer { text-align: center; font-size: 10px; color: #9ca3af; margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    .stat-box { display: inline-block; margin: 5px; padding: 8px 15px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 4px; text-align: center; }
    .stat-box .value { font-size: 18px; font-weight: bold; color: #059669; }
    .stat-box .label { font-size: 10px; color: #6b7280; }
</style>
</head>
<body>
    <div class="header">
        {$this->logoImg($logoBase64)}
        <h1>{$this->appName}</h1>
        <div class="subtitle">Sistema de Gerenciamento de Frotas</div>
    </div>

    <div class="title">{$title}</div>
    <p style="font-size:11px;color:#6b7280;margin-bottom:15px;">Gerado em: {$generatedAt}</p>

    {$rows}

    <div class="footer">
        {$this->appName} &mdash; Documento gerado automaticamente em {$generatedAt}
    </div>
</body>
</html>
HTML;
    }

    private function logoImg(string $base64): string
    {
        if ($base64) {
            return "<img src=\"{$base64}\" alt=\"{$this->appName}\" />";
        }
        return '';
    }

    private function buildGeneralRows(array $data): string
    {
        $u = $data['users'] ?? [];
        $v = $data['vehicles'] ?? [];
        $t = $data['trips'] ?? [];
        $m = $data['maintenances'] ?? [];

        return '
        <table>
            <tr><th colspan="2">Usuarios</th></tr>
            <tr><td>Total</td><td>' . ($u['total'] ?? 0) . '</td></tr>
            <tr><td>Ativos</td><td>' . ($u['active'] ?? 0) . '</td></tr>
            <tr><td>Bloqueados</td><td>' . ($u['blocked'] ?? 0) . '</td></tr>
        </table>
        <table>
            <tr><th colspan="2">Veiculos</th></tr>
            <tr><td>Total</td><td>' . ($v['total'] ?? 0) . '</td></tr>
            <tr><td>Ativos</td><td>' . ($v['active'] ?? 0) . '</td></tr>
        </table>
        <table>
            <tr><th colspan="2">Viagens</th></tr>
            <tr><td>Total</td><td>' . ($t['total'] ?? 0) . '</td></tr>
            <tr><td>Pendentes</td><td>' . ($t['pending'] ?? 0) . '</td></tr>
            <tr><td>Em Andamento</td><td>' . ($t['in_progress'] ?? 0) . '</td></tr>
            <tr><td>Concluidas</td><td>' . ($t['completed'] ?? 0) . '</td></tr>
            <tr><td>No Mes</td><td>' . ($t['this_month'] ?? 0) . '</td></tr>
        </table>
        <table>
            <tr><th colspan="2">Manutencoes</th></tr>
            <tr><td>Total</td><td>' . ($m['total'] ?? 0) . '</td></tr>
            <tr><td>Pendentes</td><td>' . ($m['pending'] ?? 0) . '</td></tr>
            <tr><td>Concluidas</td><td>' . ($m['completed'] ?? 0) . '</td></tr>
            <tr><td>No Mes</td><td>' . ($m['this_month'] ?? 0) . '</td></tr>
        </table>';
    }

    private function buildUserRows(array $data): string
    {
        $status = $data['total_by_status'] ?? [];
        $users = $data['recent_users'] ?? [];

        $html = '
        <table>
            <tr><th colspan="2">Resumo</th></tr>
            <tr><td>Total</td><td>' . ($status['total'] ?? 0) . '</td></tr>
            <tr><td>Ativos</td><td>' . ($status['active'] ?? 0) . '</td></tr>
            <tr><td>Inativos</td><td>' . ($status['inactive'] ?? 0) . '</td></tr>
        </table>
        <div style="margin-top:15px;font-weight:bold;font-size:13px;">Ultimos Usuarios</div>
        <table>
            <tr><th>Nome</th><th>E-mail</th><th>Cadastro</th></tr>';

        foreach ($users as $u) {
            $name = esc($u['name'] ?? '');
            $email = esc($u['email'] ?? '');
            $created = esc($u['created_at'] ?? '');
            $html .= "<tr><td>{$name}</td><td>{$email}</td><td>{$created}</td></tr>";
        }

        $html .= '</table>';
        return $html;
    }

    private function buildVehicleRows(array $data): string
    {
        $stats = $data['stats'] ?? [];
        $vehicles = $data['recent_vehicles'] ?? [];

        $html = '
        <table>
            <tr><th colspan="2">Resumo</th></tr>
            <tr><td>Total</td><td>' . ($stats['total'] ?? 0) . '</td></tr>
            <tr><td>Ativos</td><td>' . ($stats['active'] ?? 0) . '</td></tr>
        </table>
        <div style="margin-top:15px;font-weight:bold;font-size:13px;">Ultimos Veiculos</div>
        <table>
            <tr><th>Marca</th><th>Modelo</th><th>Placa</th></tr>';

        foreach ($vehicles as $v) {
            $brand = esc($v['brand'] ?? '');
            $model = esc($v['model'] ?? '');
            $plate = esc($v['plate'] ?? '-');
            $html .= "<tr><td>{$brand}</td><td>{$model}</td><td>{$plate}</td></tr>";
        }

        $html .= '</table>';
        return $html;
    }

    private function buildTripRows(array $data): string
    {
        $stats = $data['stats'] ?? [];
        $trips = $data['recent_trips'] ?? [];

        $html = '
        <table>
            <tr><th colspan="2">Resumo</th></tr>
            <tr><td>Total</td><td>' . ($stats['total'] ?? 0) . '</td></tr>
            <tr><td>Pendentes</td><td>' . ($stats['pending'] ?? 0) . '</td></tr>
            <tr><td>Em Andamento</td><td>' . ($stats['in_progress'] ?? 0) . '</td></tr>
            <tr><td>Concluidas</td><td>' . ($stats['completed'] ?? 0) . '</td></tr>
        </table>
        <div style="margin-top:15px;font-weight:bold;font-size:13px;">Ultimas Viagens</div>
        <table>
            <tr><th>Origem</th><th>Destino</th><th>Status</th></tr>';

        foreach ($trips as $t) {
            $origin = esc($t['origin'] ?? '');
            $destination = esc($t['destination'] ?? '');
            $status = esc($t['status'] ?? '');
            $statusLabel = match ($status) {
                'pending'     => 'Pendente',
                'in_progress' => 'Em Andamento',
                'completed'   => 'Concluida',
                default       => $status,
            };
            $html .= "<tr><td>{$origin}</td><td>{$destination}</td><td>{$statusLabel}</td></tr>";
        }

        $html .= '</table>';
        return $html;
    }

    private function buildMaintenanceRows(array $data): string
    {
        $stats = $data['stats'] ?? [];
        $maintenances = $data['recent_maintenances'] ?? [];

        $html = '
        <table>
            <tr><th colspan="2">Resumo</th></tr>
            <tr><td>Total</td><td>' . ($stats['total'] ?? 0) . '</td></tr>
            <tr><td>Pendentes</td><td>' . ($stats['pending'] ?? 0) . '</td></tr>
            <tr><td>Concluidas</td><td>' . ($stats['completed'] ?? 0) . '</td></tr>
        </table>
        <div style="margin-top:15px;font-weight:bold;font-size:13px;">Ultimas Manutencoes</div>
        <table>
            <tr><th>Servico</th><th>Data</th><th>Status</th></tr>';

        foreach ($maintenances as $m) {
            $service = esc($m['service'] ?? '');
            $date = esc($m['scheduled_date'] ?? '');
            $completed = !empty($m['completed']);
            $statusLabel = $completed ? 'Concluida' : 'Pendente';
            $html .= "<tr><td>{$service}</td><td>{$date}</td><td>{$statusLabel}</td></tr>";
        }

        $html .= '</table>';
        return $html;
    }
}
