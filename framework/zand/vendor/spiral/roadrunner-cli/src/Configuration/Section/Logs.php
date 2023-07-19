<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Logs extends AbstractSection
{
    private const NAME = 'logs';

    public function render(): array
    {
        return [
            self::NAME => [
                'mode' => 'development',
                'level' => 'debug',
//                'encoding' => 'console',
//                'line_ending' => '\n',
//                'output' => 'stderr',
//                'err_output' => 'stderr',
//                'file_logger_options' => [
//                    'log_output' => '/tmp/my.log',
//                    'max_size' => 100,
//                    'max_age' => 1,
//                    'max_backups' => 5,
//                    'compress' => false
//                ],
//                'channels' => [
//                    'http' => [
//                        'mode' => 'development',
//                        'level' => 'panic',
//                        'encoding' => 'console',
//                        'output' => 'stdout',
//                        'err_output' => 'stderr'
//                    ],
//                    'server' => [
//                        'mode' => 'production',
//                        'level' => 'info',
//                        'encoding' => 'json',
//                        'output' => 'stdout',
//                        'err_output' => 'stdout'
//                    ],
//                    'rpc' => [
//                        'mode' => 'raw',
//                        'level' => 'debug',
//                        'encoding' => 'console',
//                        'output' => 'stderr',
//                        'err_output' => 'stdout'
//                    ]
//                ]
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
