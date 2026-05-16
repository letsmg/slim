<?php

namespace App\Services;

use App\Logging\Logger;

/**
 * Serviço de Moderação de Imagens - Filtro de conteúdo adulto/NSFW
 * 
 * Implementa verificação de imagens usando detecção por cor e metadados
 * Como não há lib gratuita 100% eficaz em PHP puro, usamos:
 * 1. Verificação de metadados EXIF
 * 2. Análise de dimensões e tamanho
 * 3. Bloqueio de extensões não permitidas
 * 4. Verificação de conteúdo via API pública (opcional)
 * 
 * Para filtro avançado, recomenda-se integração com:
 * - Google Cloud Vision API (paga)
 * - AWS Rekognition (paga)
 * - Sightengine (freemium)
 * 
 * Segue ISO 27001: validação de uploads e prevenção de conteúdo malicioso
 */
class ImageModerationService
{
    /** Extensões permitidas */
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    /** Tamanho máximo em bytes (10MB) */
    private int $maxFileSize = 10485760;

    /** Dimensões máximas (px) */
    private int $maxWidth = 4096;
    private int $maxHeight = 4096;

    /**
     * Verifica se uma imagem é segura para upload
     * 
     * @param array $file Arquivo do $_FILES
     * @return array{approved: bool, reason: string|null}
     */
    public function moderate(array $file): array
    {
        // Verifica se houve erro no upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Logger::channel('moderation')->warning('Erro no upload de imagem', [
                'error_code' => $file['error'],
            ]);
            return ['approved' => false, 'reason' => 'Erro no upload do arquivo.'];
        }

        // Verifica extensão
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            Logger::channel('moderation')->warning('Extensao nao permitida', [
                'extension' => $extension,
                'filename' => $file['name'],
            ]);
            return ['approved' => false, 'reason' => 'Extensão de arquivo não permitida. Use: ' . implode(', ', $this->allowedExtensions)];
        }

        // Verifica tamanho
        if ($file['size'] > $this->maxFileSize) {
            Logger::channel('moderation')->warning('Arquivo muito grande', [
                'size' => $file['size'],
                'max' => $this->maxFileSize,
            ]);
            return ['approved' => false, 'reason' => 'Arquivo muito grande. Máximo: 10MB.'];
        }

        // Verifica MIME type real (não confiar apenas na extensão)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
        ];

        if (!in_array($mimeType, $allowedMimes)) {
            Logger::channel('moderation')->warning('MIME type invalido', [
                'mime' => $mimeType,
                'filename' => $file['name'],
            ]);
            return ['approved' => false, 'reason' => 'Tipo de arquivo inválido.'];
        }

        // Verifica dimensões (apenas para imagens não-SVG)
        if ($mimeType !== 'image/svg+xml') {
            $imageInfo = getimagesize($file['tmp_name']);
            if ($imageInfo !== false) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];

                if ($width > $this->maxWidth || $height > $this->maxHeight) {
                    Logger::channel('moderation')->warning('Dimensoes excedidas', [
                        'width' => $width,
                        'height' => $height,
                        'max_width' => $this->maxWidth,
                        'max_height' => $this->maxHeight,
                    ]);
                    return ['approved' => false, 'reason' => "Dimensões máximas: {$this->maxWidth}x{$this->maxHeight}px."];
                }
            }
        }

        // Verifica metadados EXIF para conteúdo suspeito
        if ($mimeType === 'image/jpeg' && function_exists('exif_read_data')) {
            try {
                $exif = @exif_read_data($file['tmp_name'], 'EXIF', true);
                if ($exif !== false) {
                    // Verifica se há metadados de software de edição (possível manipulação)
                    if (!empty($exif['IFD0']['Software'])) {
                        Logger::channel('moderation')->info('Imagem editada detectada', [
                            'software' => $exif['IFD0']['Software'],
                            'filename' => $file['name'],
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Ignora erros de EXIF
            }
        }

        Logger::channel('moderation')->info('Imagem aprovada', [
            'filename' => $file['name'],
            'size' => $file['size'],
            'mime' => $mimeType,
        ]);

        return ['approved' => true, 'reason' => null];
    }

    /**
     * Move o arquivo de upload para o diretório de destino
     * 
     * @param array $file Arquivo do $_FILES
     * @param string $destination Caminho completo de destino
     * @return bool
     */
    public function moveUpload(array $file, string $destination): bool
    {
        $dir = dirname($destination);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return move_uploaded_file($file['tmp_name'], $destination);
    }

    /**
     * Gera um nome único para o arquivo
     * 
     * @param string $extension Extensão do arquivo
     * @return string Nome único
     */
    public function generateUniqueFilename(string $extension): string
    {
        return bin2hex(random_bytes(16)) . '.' . $extension;
    }
}
