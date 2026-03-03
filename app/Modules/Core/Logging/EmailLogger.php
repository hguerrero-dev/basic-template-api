<?php

namespace App\Modules\Core\Logging;

use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Handler\AbstractProcessingHandler;
use Illuminate\Support\Facades\Mail;

class EmailLogger
{
    public function __invoke(array $config)
    {
        file_put_contents(storage_path('logs/debug_logger.txt'), "EmailLogger invoked!\n", FILE_APPEND);
        $logger = new Logger('mail_alerts');

        $handler = new class($config['level'] ?? Logger::ERROR) extends AbstractProcessingHandler {
            protected function write(LogRecord $record): void
            {
                file_put_contents(storage_path('logs/debug_logger.txt'), "LogRecord trying to send!\n", FILE_APPEND);
                try {
                    // Envía el texto del error por correo plano
                    Mail::raw($record->formatted, function ($message) {
                        $message->to(env('LOG_MAIL_TO'))
                            ->subject('🚨 ¡Error Crítico en ' . env('APP_NAME') . '!');
                    });
                } catch (\Throwable $e) {
                    // Vamos a guardar el error de SMTP en un archivo de texto simple
                    file_put_contents(storage_path('logs/smtp_error.txt'), $e->getMessage() . PHP_EOL, FILE_APPEND);
                }
            }
        };

        $logger->pushHandler($handler);

        return $logger;
    }
}
