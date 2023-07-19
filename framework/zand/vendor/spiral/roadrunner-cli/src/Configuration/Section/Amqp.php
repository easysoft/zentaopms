<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Amqp extends AbstractSection
{
    private const NAME = 'amqp';

    public function render(): array
    {
        return [
            self::NAME => [
                'addr' => 'amqp://guest:guest@127.0.0.1:5672/'
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
