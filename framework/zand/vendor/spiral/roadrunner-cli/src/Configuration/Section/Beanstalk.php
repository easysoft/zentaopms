<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Beanstalk extends AbstractSection
{
    private const NAME = 'beanstalk';

    public function render(): array
    {
        return [
            self::NAME => [
                'addr' => 'tcp://127.0.0.1:11300',
                'timeout' => '10s'
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
