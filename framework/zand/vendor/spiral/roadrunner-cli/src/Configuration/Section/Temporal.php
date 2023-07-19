<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Temporal extends AbstractSection
{
    private const NAME = 'temporal';

    public function render(): array
    {
        return [
            self::NAME => [
                'address' => '127.0.0.1:7233',
//                'cache_size' => 10000,
//                'namespace' => 'default',
//                'codec' => 'proto',
//                'debug_level' => 2,
//                'metrics' => [
//                    'address' => '127.0.0.1:9091',
//                    'type' => 'summary',
//                    'prefix' => 'foobar'
//                ],
//                'activities' => [
//                    'debug' => false,
//                    'command' => 'php my-super-app.php',
//                    'num_workers' => 0,
//                    'max_jobs' => 64,
//                    'allocate_timeout' => '60s',
//                    'destroy_timeout' => '60s',
//                    'supervisor' => [
//                        'watch_tick' => '1s',
//                        'ttl' => '0s',
//                        'idle_ttl' => '10s',
//                        'max_worker_memory' => 128,
//                        'exec_ttl' => '60s'
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
