<?php

namespace App\Controllers;

use mindplay\vite\Manifest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    private Manifest $vite;

    public function __construct()
    {
        // Detecta se está em modo dev (Vite rodando) ou produção
        $isDev = $this->isViteRunning();

        // Caminho do manifest.json gerado pelo Vite no build de produção
        $manifestPath = __DIR__ . '/../../public/manifest.json';

        // Base path dos assets (deve bater com o 'base' do vite.config.js)
        $basePath = '/';

        $this->vite = new Manifest($isDev, $manifestPath, $basePath);
    }

    /**
     * Página inicial - serve o SPA (Vue.js)
     * Renderiza o index.html com os assets gerenciados pelo Vite
     * Em dev: injeta scripts do Vite Dev Server (HMR)
     * Em produção: usa manifest.json para cache busting
     */
    public function index(Request $request, Response $response): Response
    {
        $htmlPath = __DIR__ . '/../../resources/index.html';

        if (!file_exists($htmlPath)) {
            $htmlPath = __DIR__ . '/../../public/index.html';
        }

        $html = file_get_contents($htmlPath);

        // Gera as tags do Vite para o entry point 'js/app.js'
        $tags = $this->vite->createTags('js/app.js');

        // Substitui os placeholders no HTML
        $html = str_replace(
            ['<!-- VITE_JS -->', '<!-- VITE_CSS -->'],
            [$tags->js, $tags->css],
            $html
        );

        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Verifica se o Vite Dev Server está rodando
     * Tenta conectar na porta 5175 (IPv4 e IPv6)
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
