<?php

namespace App\Controllers;

use App\Services\ReportService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de Report - Gerencia requisições HTTP de relatórios
 * Segue ISO 27001: sanitização de entrada e escape de saída
 */
class ReportController
{
    public function __construct(
        private ReportService $service
    ) {}

    /**
     * GET /api/reports - Lista todos os relatórios disponíveis
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
     * GET /api/reports/{type} - Gera relatório específico
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $type = sanitize_input($args['type'] ?? '');

        $report = $this->service->generate($type);

        if ($report === null) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Tipo de relatório inválido',
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
     * GET /api/reports/{type}/pdf - Download do relatório em PDF
     */
    public function pdf(Request $request, Response $response, array $args): Response
    {
        $type = sanitize_input($args['type'] ?? '');

        $pdfContent = $this->service->generatePdf($type);

        if ($pdfContent === null) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Tipo de relatório inválido',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $filename = match ($type) {
            'general'  => 'relatorio-geral',
            'users'    => 'relatorio-usuarios',
            'products' => 'relatorio-produtos',
            default    => 'relatorio',
        };

        $response->getBody()->write($pdfContent);
        return $response
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '-' . date('Y-m-d') . '.pdf"')
            ->withHeader('Content-Length', (string) strlen($pdfContent));
    }
}
