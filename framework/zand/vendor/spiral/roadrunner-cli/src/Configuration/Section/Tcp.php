<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Tcp extends AbstractSection
{
    private const NAME = 'tcp';

    public function render(): array
    {
        return [
            self::NAME => [
                'servers' => [
                    'server1' => [
                        'addr' => '127.0.0.1:7778',
//                        'delimiter' => '\r\n',
//                        'read_buf_size' => 1
                    ],
//                    'server2' => [
//                        'addr' => '127.0.0.1:8811',
//                        'read_buf_size' => 10
//                    ],
//                    'server3' => [
//                        'addr' => '127.0.0.1:8812',
//                        'delimiter' => '\r\n',
//                        'read_buf_size' => 1
//                    ]
                ],
                'pool' => [
                    'command' => '',
                    'num_workers' => 5,
                    'max_jobs' => 0,
                    'allocate_timeout' => '60s',
                    'destroy_timeout' => '60s'
                ]
            ]
        ];
    }

    public function getRequired(): array
    {
        return [
            Server::class
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
