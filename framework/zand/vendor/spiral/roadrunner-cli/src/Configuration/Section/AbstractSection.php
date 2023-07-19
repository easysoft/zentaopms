<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

abstract class AbstractSection implements SectionInterface
{
    public function getRequired(): array
    {
        return [];
    }

    abstract public function render(): array;
}
