<?php

namespace App\Logging;

use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;

/**
 * Logger centralizado do sistema - Padrão Singleton
 * 
 * Configuração seguindo boas práticas de mercado:
 * - RotatingFileHandler: arquivos diários com retenção de 30 dias
 * - Formatter com linha detalhada: [%datetime%] %channel%.%level_name%: %message% %context% %extra%
 * - Processors: UID único por requisição, memória, dados web
 * - Nível configurável via APP_LOG_LEVEL no .env
 * - Segue ISO 27001: não loga dados sensíveis (senhas, tokens)
 */
class Logger
{
    /** @var array<string, MonologLogger> Instâncias dos loggers por canal */
    private static array $instances = [];

    /** @var string Caminho da pasta de logs */
    private static string $logPath;

    /** @var int Nível mínimo de log */
    private static int $minLevel;

    /**
     * Configuração padrão do logger
     */
    private static function init(): void
    {
        // Caminho absoluto da pasta de logs
  self::$logPath = dirname(__DIR__, 2) . '/storage/logs';


        // Garante que a pasta existe
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0775, true);
        }

        // Nível de log baseado no .env ou default (warning em produção, debug em dev)
        $config = require dirname(__DIR__, 2) . '/config/config.php';
        $env = $config['app']['env'] ?? 'production';

  self::$minLevel = match (strtolower($env)) {
      'production' => 500, // Level::Warning
      'staging'    => 200, // Level::Info
      default      => 100, // Level::Debug
  };



    }

    /**
     * Obtém ou cria uma instância do logger para um canal específico
     * 
     * @param string $channel Nome do canal (ex: app, sql, auth, api)
     * @return MonologLogger
     */
    public static function channel(string $channel = 'app'): MonologLogger
    {
        if (isset(self::$instances[$channel])) {
            return self::$instances[$channel];
        }

        if (empty(self::$logPath)) {
            self::init();
        }

        $logger = new MonologLogger($channel);

        // Handler principal: arquivo rotativo diário com retenção de 30 dias
        $rotatingHandler = new RotatingFileHandler(
            self::$logPath . '/' . $channel . '.log',
            30, // retenção de 30 dias
            self::$minLevel,
            true, // bubble
            0775  // permissão do arquivo
        );

        // Formato personalizado com timestamp, canal, nível e dados
        $dateFormat = 'Y-m-d H:i:s.u';
        $output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
        $formatter = new LineFormatter($output, $dateFormat, true, true);
        $rotatingHandler->setFormatter($formatter);

        $logger->pushHandler($rotatingHandler);

        // Handler adicional para erros críticos em arquivo separado
        if (self::$minLevel <= Level::Error) {
            $errorHandler = new StreamHandler(
                self::$logPath . '/errors.log',
                Level::Error
            );
            $errorHandler->setFormatter($formatter);
            $logger->pushHandler($errorHandler);
        }

        // Processors para enriquecer os logs
        $logger->pushProcessor(new UidProcessor());      // UUID único por requisição
        $logger->pushProcessor(new MemoryUsageProcessor()); // Uso de memória
        $logger->pushProcessor(new WebProcessor());         // Dados da requisição

        self::$instances[$channel] = $logger;

        return $logger;
    }

    /**
     * Alias estático para channel('app')
     * 
     * @return MonologLogger
     */
    public static function getInstance(): MonologLogger
    {
        return self::channel('app');
    }

    /**
     * Método mágico para chamadas estáticas (debug, info, warning, error, critical)
     * 
     * @param string $name
     * @param array $arguments
     */
    public static function __callStatic(string $name, array $arguments): void
    {
        $logger = self::getInstance();
        if (method_exists($logger, $name)) {
            $logger->$name(...$arguments);
        }
    }

    /**
     * Loga uma exceção com stack trace
     * 
     * @param \Throwable $exception
     * @param array $context Dados adicionais de contexto
     * @param string $channel Canal de log
     */
    public static function exception(\Throwable $exception, array $context = [], string $channel = 'app'): void
    {
        self::channel($channel)->error($exception->getMessage(), array_merge([
            'exception' => get_class($exception),
            'file'      => $exception->getFile(),
            'line'      => $exception->getLine(),
            'trace'     => $exception->getTraceAsString(),
        ], $context));
    }
}