<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Configuration\Section;

final class Fileserver extends AbstractSection
{
    private const NAME = 'fileserver';

    public function render(): array
    {
        return [
            self::NAME => [
                'address' => '127.0.0.1:10101',
                'calculate_etag' => true,
                'weak' => false,
                'stream_request_body' => true,
//                'serve' => [
//                    [
//                        'prefix' => '/foo',
//                        'root' => '../../../tests',
//                        'compress' => false,
//                        'cache_duration' => 10,
//                        'max_age' => 10,
//                        'bytes_range' => true
//                    ],
//                    [
//                        'prefix' => '/foo/bar',
//                        'root' => '../../../tests',
//                        'compress' => false,
//                        'cache_duration' => 10,
//                        'max_age' => 10,
//                        'bytes_range' => true
//                    ]
//                ]
            ]
        ];
    }

    public static function getShortName(): string
    {
        return self::NAME;
    }
}
