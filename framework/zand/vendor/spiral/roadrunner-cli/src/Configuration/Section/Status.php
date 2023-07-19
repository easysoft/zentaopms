<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Status extends AbstractSection
{
    private const NAME = 'status';

    public function render(): array
    {
        return [
            self::NAME => [
                'address' => '127.0.0.1:2114',
                'unavailable_status_code' => 503
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
