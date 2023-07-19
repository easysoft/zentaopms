<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration;

use Spiral\RoadRunner\Console\Configuration\Section\Http;
use Spiral\RoadRunner\Console\Configuration\Section\Jobs;

final class Presets
{
    public const WEB_PRESET_NANE = 'web';

    public const WEB_PLUGINS = [
        Http::class,
        Jobs::class
    ];
}
