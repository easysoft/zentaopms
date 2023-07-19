<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Reload extends AbstractSection
{
    private const NAME = 'reload';

    public function render(): array
    {
        return [
            self::NAME => [
                'interval' => '1s',
                'patterns' => [
                    '.php'
                ],
                'services' => [
                    'http' => [
                        'dirs' => [
                            '.'
                        ],
                        'recursive' => true,
                        'ignore' => [
                            'vendor'
                        ],
                        'patterns' => [
                            '.php',
                            '.go',
                            '.md'
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
