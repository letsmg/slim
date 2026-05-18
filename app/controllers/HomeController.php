<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    /**
     * Página inicial - serve o SPA (Vue.js)
     * Renderiza o index.html com os assets compilados pelo Vite
     * 
     * Em modo dev (Vite rodando), injeta os scripts do Vite Dev Server
     * Em produção, usa os arquivos compilados em /js/app.js e /css/app.css
     */
    public function index(Request $request, Response $response): Response
    {
        $htmlPath = __DIR__ . '/../../resources/index.html';

        if (!file_exists($htmlPath)) {
            $htmlPath = __DIR__ . '/../../public/index.html';
        }

        $html = file_get_contents($htmlPath);

        // Verifica se o Vite Dev Server está rodando (modo desenvolvimento)
        $viteRunning = $this->isViteRunning();

        if ($viteRunning) {
            // Modo dev: injeta os scripts do Vite Dev Server
            // O Vite injeta automaticamente o client HMR e processa os módulos
            $viteScript = '<script type="module" src="http://localhost:5175/@vite/client"></script>';
            $viteScript .= "\n    <script type=\"module\" src=\"http://localhost:5175/js/app.js\"></script>";

            // Remove os links de CSS/JS estáticos e adiciona os do Vite
            $html = preg_replace(
                '/<link rel="stylesheet" href="\/css\/app\.css" \/>/',
                '',
                $html
            );
            $html = preg_replace(
                '/<script type="module" src="\/js\/app\.js"><\/script>/',
                $viteScript,
                $html
            );
        }

        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Verifica se o Vite Dev Server está rodando
     * Tenta conectar em IPv4 (127.0.0.1) e IPv6 (::1)
     */
    private function isViteRunning(): bool
    {
        static $running = null;
        if ($running !== null) {
            return $running;
        }

        $hosts = ['127.0.0.1', '::1', 'localhost'];
        foreach ($hosts as $host) {
            $fp = @fsockopen($host, 5175, $errno, $errstr, 0.3);
            if ($fp) {
                fclose($fp);
                $running = true;
                return true;
            }
        }

        $running = false;
        return false;
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
