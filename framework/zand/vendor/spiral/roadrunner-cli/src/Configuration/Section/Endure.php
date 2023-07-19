<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Endure extends AbstractSection
{
    private const NAME = 'endure';

    public function render(): array
    {
        return [
            self::NAME => [
                'grace_period' => '30s',
                'print_graph' => false,
                'log_level' => 'error'
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
