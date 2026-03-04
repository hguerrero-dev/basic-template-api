<?php

namespace App\Modules\Core\Logging;

use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Handler\AbstractProcessingHandler;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('email');

        $handler = new class($config['level'] ?? Logger::ERROR, $config) extends AbstractProcessingHandler {
            private array $config;

            public function __construct(int|string $level, array $config)
            {
                parent::__construct($level);
                $this->config = $config;
            }

            protected function write(LogRecord $record): void
            {
                try {
                    Mail::raw($record->formatted, function ($message) {
                        $to = $this->config['with']['to'] ?? 'admin@ejemplo.com';
                        $subject = $this->config['with']['subject'] ?? 'Error Crítico';
                        $from = $this->config['with']['from'] ?? 'noreply@ejemplo.com';

                        $message->to($to)
                            ->from($from)
                            ->subject($subject);
                    });
                } catch (\Throwable $e) {
                    Log::channel('single')->error('Error enviando alerta por email: ' . $e->getMessage());
                }
            }
        };

        $logger->pushHandler($handler);

        return $logger;
    }
}
