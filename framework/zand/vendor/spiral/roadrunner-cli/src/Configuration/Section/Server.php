<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Server extends AbstractSection
{
    private const NAME = 'server';

    public function render(): array
    {
        return [
            self::NAME => [
                'command' => 'php app.php',
                'relay' => 'pipes'
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
