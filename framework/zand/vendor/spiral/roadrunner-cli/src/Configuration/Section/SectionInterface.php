<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

interface SectionInterface
{
    public function render(): array;

    public function getRequired(): array;

    public static function getShortName(): string;
}
