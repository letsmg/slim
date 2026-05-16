<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    /**
     * Página inicial - serve o SPA (Vue.js)
     * Renderiza o index.html com os assets compilados pelo Vite
     * Os arquivos app.css e app.js são referenciados diretamente no HTML
     */
    public function index(Request $request, Response $response): Response
    {
        $htmlPath = __DIR__ . '/../../public/index.html';

        if (!file_exists($htmlPath)) {
            $htmlPath = __DIR__ . '/../../resources/index.html';
        }

        $html = file_get_contents($htmlPath);

        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Exemplo de endpoint com dados sanitizados na entrada e escapados na saída
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);

        // Dados simulados — em produção viriam do banco via UserService
        $user = [
            'id'    => $id,
            'name'  => 'Exemplo ' . $id,
            'email' => 'usuario' . $id . '@exemplo.com',
        ];

        // Monta resposta em JSON escapando os valores
        $payload = json_encode([
            'id'    => $user['id'],
            'name'  => esc($user['name']),
            'email' => esc($user['email']),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Endpoint de contato com sanitização de entrada e validação CSRF
     */
    public function contact(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        // Sanitiza todos os dados de entrada
        $data = sanitize_inputs($data);

        // Valida email
        if (!validate_email($data['email'] ?? '')) {
            $result = json_encode(['error' => 'Email inválido']);
            $response->getBody()->write($result);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        // Simula processamento (em produção: salvar no banco)
        $result = json_encode([
            'message' => 'Contato recebido com sucesso',
            'name'    => esc($data['name'] ?? ''),
            'email'   => esc($data['email'] ?? ''),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($result);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
