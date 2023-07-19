<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Boltdb extends AbstractSection
{
    private const NAME = 'boltdb';

    public function render(): array
    {
        return [
            self::NAME => [
                'permissions' => 0777
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
