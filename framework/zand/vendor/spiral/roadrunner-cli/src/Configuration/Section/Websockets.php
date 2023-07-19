<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Websockets extends AbstractSection
{
    private const NAME = 'websockets';

    public function render(): array
    {
        return [
            self::NAME => [
                'broker' => 'default-redis',
                'allowed_origin' => '*',
                'path' => '/ws'
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
