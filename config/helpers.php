<?php
// Sanitiza uma string de entrada: remove tags HTML e espaços extras
function sanitize_input(string $value): string
{
    $value = trim($value);
    $value = strip_tags($value);
    return $value;
}

// Sanitiza um array de entradas recursivamente
function sanitize_inputs(array $data): array
{
    $sanitized = [];
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $sanitized[$key] = sanitize_inputs($value);
        } elseif (is_string($value)) {
            $sanitized[$key] = sanitize_input($value);
        } else {
            $sanitized[$key] = $value;
        }
    }
    return $sanitized;
}

// Gera hash de senha com Argon2id (protegido contra ataques de GPU/VRAM)
function hash_password(string $password): string
{
    $options = [
        'memory_cost' => 65536,  // 64 MB
        'time_cost'   => 4,      // 4 iterações
        'threads'     => 3,      // 3 threads paralelas
    ];

    $hash = password_hash($password, PASSWORD_ARGON2ID, $options);
    if ($hash === false) {
        throw new RuntimeException('Falha ao gerar hash de senha');
    }
    return $hash;
}

// Verifica senha contra hash Argon2id
// Timing-safe: tempo constante independente da senha estar correta ou não
function verify_password(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

// Verifica se hash precisa ser recalculado (para rehash automático no login)
function needs_rehash(string $hash): bool
{
    $options = [
        'memory_cost' => 65536,
        'time_cost'   => 4,
        'threads'     => 3,
    ];
    return password_needs_rehash($hash, PASSWORD_ARGON2ID, $options);
}

// Gera um token seguro aleatório (para CSRF, reset de senha, etc.)
function generate_secure_token(int $length = 32): string
{
    return bin2hex(random_bytes($length));
}

// Valida se um email tem formato válido
function validate_email(string $email): bool
{
    $email = sanitize_input($email);
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Carrega o manifest.json do Vite e retorna as URLs dos assets compilados
// 
// @return array{js: string, css: string}
function get_vite_assets(): array
{
    static $assets = null;

    if ($assets !== null) {
        return $assets;
    }

    $manifestPath = __DIR__ . '/../public/.vite/manifest.json';

    if (!file_exists($manifestPath)) {
        // Fallback para desenvolvimento
        $assets = [
            'js'  => '/js/app.js',
            'css' => '/css/app.css',
        ];
        return $assets;
    }

    $manifest = json_decode(file_get_contents($manifestPath), true);

    $assets = [
        'js'  => '',
        'css' => '',
    ];

    foreach ($manifest as $entry) {
        // Procura pelo entry point principal 'app'
        if (isset($entry['isEntry']) && $entry['isEntry']) {
            if (!empty($entry['file'])) {
                $assets['js'] = '/' . $entry['file'];
            }
            if (!empty($entry['css'])) {
                $assets['css'] = '/' . $entry['css'][0];
            }
        }
    }

    return $assets;
}
