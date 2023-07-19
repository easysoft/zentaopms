<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Otel extends AbstractSection
{
    private const NAME = 'otel';

    public function render(): array
    {
        return [
            self::NAME => [
                'insecure' => false,
                'compress' => false,
                'client' => 'http',
                'exporter' => 'otlp',
                'custom_url' => '',
                'service_name' => 'RoadRunner',
                'service_version' => '1.0.0',
                'endpoint' => '127.0.0.1:4318'
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
