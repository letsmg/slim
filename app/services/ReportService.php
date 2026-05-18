<?php

namespace App\Services;

use App\Repositories\ReportRepository;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Service de Report - Camada de lógica para relatórios
 * Orquestra a geração de relatórios a partir dos repositórios
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
     * Lista todos os tipos de relatório disponíveis
     * 
     * @return array
     */
    public function list(): array
    {
        return [
            [
                'id'          => 'general',
                'name'        => 'Relatório Geral',
                'description' => 'Estatísticas gerais do sistema (usuários e produtos)',
            ],
            [
                'id'          => 'users',
                'name'        => 'Relatório de Usuários',
                'description' => 'Detalhamento de usuários cadastrados e status',
            ],
            [
                'id'          => 'products',
                'name'        => 'Relatório de Produtos',
                'description' => 'Detalhamento de produtos, estoque e preços',
            ],
        ];
    }

    /**
     * Gera um relatório baseado no tipo
     * 
     * @param string $type Tipo do relatório (general, users, products)
     * @return array|null
     */
    public function generate(string $type): ?array
    {
        return match ($type) {
            'general'  => $this->repository->getGeneralStats(),
            'users'    => $this->repository->getUserReport(),
            'products' => $this->repository->getProductReport(),
            default    => null,
        };
    }

    /**
     * Gera um PDF do relatório e retorna o conteúdo binário
     * 
     * @param string $type Tipo do relatório
     * @return string|null Conteúdo binário do PDF ou null se tipo inválido
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
     * Monta o HTML do relatório com cabeçalho padronizado (logo + nome do sistema)
     */
    private function buildHtml(string $type, array $data): string
    {
        $title = match ($type) {
            'general'  => 'Relatório Geral',
            'users'    => 'Relatório de Usuários',
            'products' => 'Relatório de Produtos',
            default    => 'Relatório',
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
            case 'products':
                $rows = $this->buildProductRows($data);
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
        $p = $data['products'] ?? [];

        return '
        <table>
            <tr><th colspan="2">Usuários</th></tr>
            <tr><td>Total</td><td>' . ($u['total'] ?? 0) . '</td></tr>
            <tr><td>Ativos</td><td>' . ($u['active'] ?? 0) . '</td></tr>
            <tr><td>Bloqueados</td><td>' . ($u['blocked'] ?? 0) . '</td></tr>
        </table>
        <table>
            <tr><th colspan="2">Produtos</th></tr>
            <tr><td>Total</td><td>' . ($p['total'] ?? 0) . '</td></tr>
            <tr><td>Ativos</td><td>' . ($p['active'] ?? 0) . '</td></tr>
            <tr><td>Inativos</td><td>' . ($p['inactive'] ?? 0) . '</td></tr>
            <tr><td>Estoque Total</td><td>' . ($p['total_stock'] ?? 0) . '</td></tr>
            <tr><td>Preço Médio</td><td>R$ ' . number_format($p['avg_price'] ?? 0, 2, ',', '.') . '</td></tr>
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
        <div style="margin-top:15px;font-weight:bold;font-size:13px;">Últimos Usuários</div>
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

    private function buildProductRows(array $data): string
    {
        $stats = $data['stats'] ?? [];
        $products = $data['recent_products'] ?? [];

        $html = '
        <table>
            <tr><th colspan="2">Resumo</th></tr>
            <tr><td>Total</td><td>' . ($stats['total'] ?? 0) . '</td></tr>
            <tr><td>Ativos</td><td>' . ($stats['active'] ?? 0) . '</td></tr>
            <tr><td>Inativos</td><td>' . ($stats['inactive'] ?? 0) . '</td></tr>
            <tr><td>Estoque Total</td><td>' . ($stats['total_stock'] ?? 0) . '</td></tr>
            <tr><td>Preço Médio</td><td>R$ ' . number_format($stats['avg_price'] ?? 0, 2, ',', '.') . '</td></tr>
        </table>
        <div style="margin-top:15px;font-weight:bold;font-size:13px;">Últimos Produtos</div>
        <table>
            <tr><th>Nome</th><th>Preço</th><th>Estoque</th></tr>';

        foreach ($products as $p) {
            $name = esc($p['name'] ?? '');
            $price = 'R$ ' . number_format((float)($p['price'] ?? 0), 2, ',', '.');
            $stock = esc((string)($p['stock'] ?? 0));
            $html .= "<tr><td>{$name}</td><td>{$price}</td><td>{$stock}</td></tr>";
        }

        $html .= '</table>';
        return $html;
    }
}
