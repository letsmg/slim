<?php
/**
 * Router para o PHP built-in server
 * 
 * Comportamento:
 * - Se o arquivo solicitado existe em public/, serve diretamente (imagens, fonts, js, css)
 * - Para tudo o resto, passa pelo Slim Framework
 * 
 * Em desenvolvimento (APP_ENV=local):
 *   O Slim injeta tags apontando para o Vite Dev Server (localhost:5173)
 *   Os assets JS/CSS sao servidos pelo Vite, nao pelo PHP
 * 
 * Em producao (APP_ENV=production):
 *   O Slim injeta tags apontando para os assets compilados em public/
 *   O PHP serve os arquivos estaticos diretamente
 * 
 * Uso: php -S localhost:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve arquivos estaticos diretamente se existirem
$filePath = __DIR__ . '/public' . $uri;
if ($uri !== '/' && is_file($filePath)) {
    // Determina o MIME type baseado na extensao
    $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    $mimeTypes = [
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'webp' => 'image/webp',
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
        'eot'  => 'application/vnd.ms-fontobject',
    ];

    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
    }

    readfile($filePath);
    return true;
}

// Para tudo o resto, passa pelo Slim
require __DIR__ . '/public/index.php';
