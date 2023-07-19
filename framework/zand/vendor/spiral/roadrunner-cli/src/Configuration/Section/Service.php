<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Service extends AbstractSection
{
    private const NAME = 'service';

    public function render(): array
    {
        return [
            self::NAME => [
                'some_service_1' => [
                    'command' => 'php tests/plugins/service/test_files/loop.php',
                    'env' => [
                        'foo' => 'BAR',
                        'foo2' => 'BAR2'
                    ],
                    'process_num' => 1,
                    'exec_timeout' => 0,
                    'remain_after_exit' => true,
                    'restart_sec' => 1
                ],
                'some_service_2' => [
                    'command' => 'binary',
                    'env' => [
                        'foo' => 'BAR',
                        'foo2' => 'BAR2'
                    ],
                    'process_num' => 1,
                    'exec_timeout' => 0,
                    'remain_after_exit' => true,
                    'restart_sec' => 1
                ]
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
