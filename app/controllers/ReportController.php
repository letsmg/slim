<?php

namespace App\Controllers;

use App\Services\ReportService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de Report - Gerencia requisicoes HTTP de relatorios
 * Segue ISO 27001: sanitizacao de entrada e escape de saida
 */
class ReportController
{
    public function __construct(
        private ReportService $service
    ) {}

    /**
     * GET /api/reports - Lista todos os relatorios disponiveis
     */
    public function index(Request $request, Response $response): Response
    {
        $reports = $this->service->list();

        $payload = json_encode([
            'success' => true,
            'reports' => $reports,
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/reports/{type} - Gera relatorio especifico
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $type = sanitize_input($args['type'] ?? '');

        $report = $this->service->generate($type);

        if ($report === null) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Tipo de relatorio invalido',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode([
            'success' => true,
            'report'  => $report,
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/reports/{type}/pdf/view - Visualiza o relatorio em PDF inline no navegador
     */
    public function pdfView(Request $request, Response $response, array $args): Response
    {
        $type = sanitize_input($args['type'] ?? '');

        $pdfContent = $this->service->generatePdf($type);

        if ($pdfContent === null) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Tipo de relatorio invalido',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $response->getBody()->write($pdfContent);
        return $response
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'inline; filename="relatorio-' . date('Y-m-d') . '.pdf"')
            ->withHeader('Content-Length', (string) strlen($pdfContent));
    }

    /**
     * GET /api/reports/{type}/pdf - Download do relatorio em PDF
     */
    public function pdf(Request $request, Response $response, array $args): Response
    {
        $type = sanitize_input($args['type'] ?? '');

        $pdfContent = $this->service->generatePdf($type);

        if ($pdfContent === null) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Tipo de relatorio invalido',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $filename = match ($type) {
            'general'      => 'relatorio-geral',
            'users'        => 'relatorio-usuarios',
            'vehicles'     => 'relatorio-veiculos',
            'trips'        => 'relatorio-viagens',
            'maintenances' => 'relatorio-manutencoes',
            default        => 'relatorio',
        };

        $response->getBody()->write($pdfContent);
        return $response
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '-' . date('Y-m-d') . '.pdf"')
            ->withHeader('Content-Length', (string) strlen($pdfContent));
    }

    /**
     * GET /api/reports/{type}/csv - Download do relatorio em CSV para Power BI
     */
    public function csv(Request $request, Response $response, array $args): Response
    {
        $type = sanitize_input($args['type'] ?? '');

        $csvContent = $this->service->generateCsv($type);

        if ($csvContent === null) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Tipo de relatorio invalido',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $filename = match ($type) {
            'general'      => 'relatorio-geral',
            'users'        => 'relatorio-usuarios',
            'vehicles'     => 'relatorio-veiculos',
            'trips'        => 'relatorio-viagens',
            'maintenances' => 'relatorio-manutencoes',
            default        => 'relatorio',
        };

        $response->getBody()->write($csvContent);
        return $response
            ->withHeader('Content-Type', 'text/csv; charset=utf-8')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '-' . date('Y-m-d') . '.csv"')
            ->withHeader('Content-Length', (string) strlen($csvContent));
    }
}
