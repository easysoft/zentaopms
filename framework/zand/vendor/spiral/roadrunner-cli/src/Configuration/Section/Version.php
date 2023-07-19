<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Version extends AbstractSection
{
    private const NAME = 'version';
    private const CONFIG_VERSION = '3';

    public function render(): array
    {
        return [
            self::NAME => self::CONFIG_VERSION
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
