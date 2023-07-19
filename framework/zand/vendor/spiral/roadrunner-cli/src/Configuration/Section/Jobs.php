<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Jobs extends AbstractSection
{
    private const NAME = 'jobs';

    public function render(): array
    {
        return [
            self::NAME => [
                'pool' => [
                    'num_workers' => 2,
                    'max_worker_memory' => 100
                ],
                'consume' => []
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
