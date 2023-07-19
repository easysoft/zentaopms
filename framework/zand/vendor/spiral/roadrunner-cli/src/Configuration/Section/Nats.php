<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Nats extends AbstractSection
{
    private const NAME = 'nats';

    public function render(): array
    {
        return [
            self::NAME => [
                'addr' => 'demo.nats.io'
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
