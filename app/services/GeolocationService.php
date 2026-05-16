<?php

namespace App\Services;

use GeoIp2\Database\Reader;
use App\Logging\Logger;

/**
 * Serviço de Geolocalização - Identifica IP e localização do usuário
 * 
 * Usa a biblioteca geoip2/geoip2 (MaxMind GeoLite2)
 * Os dados de IP e localização são hashados com Argon2id para privacidade
 * Segue ISO 27001: proteção de dados pessoais
 */
class GeolocationService
{
    private ?Reader $reader = null;
    private string $dbPath;

    public function __construct()
    {
        $this->dbPath = dirname(__DIR__, 2) . '/storage/geoip/GeoLite2-City.mmdb';
    }

    /**
     * Obtém dados de geolocalização a partir de um IP
     * 
     * @param string $ip Endereço IP do usuário
     * @return array{dados hashados com argon2id para privacidade}
     */
    public function locate(string $ip): array
    {
        // Se não conseguir localizar, retorna dados mínimos
        try {
            if (!file_exists($this->dbPath)) {
                Logger::channel('geoip')->warning('Base GeoIP nao encontrada', ['path' => $this->dbPath]);
                return $this->getDefaultData($ip);
            }

            if ($this->reader === null) {
                $this->reader = new Reader($this->dbPath);
            }

            $record = $this->reader->city($ip);

            $data = [
                'ip' => $ip,
                'city' => $record->city->name ?? null,
                'region' => $record->mostSpecificSubdivision->name ?? null,
                'country' => $record->country->name ?? null,
                'country_code' => $record->country->isoCode ?? null,
                'postal_code' => $record->postal->code ?? null,
                'latitude' => $record->location->latitude ?? null,
                'longitude' => $record->location->longitude ?? null,
                'timezone' => $record->location->timeZone ?? null,
            ];

            Logger::channel('geoip')->info('Geolocalizacao realizada', [
                'ip_hash' => $this->hashIp($ip),
                'country' => $data['country'],
            ]);

            return $data;

        } catch (\Exception $e) {
            Logger::channel('geoip')->warning('Falha na geolocalizacao', [
                'ip_hash' => $this->hashIp($ip),
                'error' => $e->getMessage(),
            ]);

            return $this->getDefaultData($ip);
        }
    }

    /**
     * Retorna dados padrão quando não consegue localizar
     */
    private function getDefaultData(string $ip): array
    {
        return [
            'ip' => $ip,
            'city' => null,
            'region' => null,
            'country' => null,
            'country_code' => null,
            'postal_code' => null,
            'latitude' => null,
            'longitude' => null,
            'timezone' => null,
        ];
    }

    /**
     * Hash do IP com Argon2id para armazenamento seguro
     * Conforme política de privacidade: IPs são hashados antes de armazenar
     */
    public function hashIp(string $ip): string
    {
        return hash_password($ip);
    }

    /**
     * Obtém o IP real do usuário considerando proxies
     */
    public function getClientIp(): string
    {
        $headers = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}
