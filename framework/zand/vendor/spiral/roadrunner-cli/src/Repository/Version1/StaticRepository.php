<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Repository\Version1;

use Spiral\RoadRunner\Console\Repository\AssetInterface;
use Spiral\RoadRunner\Console\Repository\GitHub\GitHubAsset;
use Spiral\RoadRunner\Console\Repository\GitHub\GitHubRelease;
use Spiral\RoadRunner\Console\Repository\ReleaseInterface;
use Spiral\RoadRunner\Console\Repository\ReleasesCollection;
use Spiral\RoadRunner\Console\Repository\RepositoryInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class StaticRepository implements RepositoryInterface
{
    /**
     * @var array<array{name: string, version: string, assets: array<array{name: string, uri: string}>}>
     */
    private const RELEASES_DATA = [
        [
            'name' => '1.9.1',
            'version' => 'v1.9.1',
            'assets' => [
                [
                    'name' => 'roadrunner-1.9.1-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.9.1/roadrunner-1.9.1-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.9.1-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.9.1/roadrunner-1.9.1-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.9.1-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.9.1/roadrunner-1.9.1-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.9.1-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.9.1/roadrunner-1.9.1-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.9.1-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.9.1/roadrunner-1.9.1-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.9.0',
            'version' => 'v1.9.0',
            'assets' => [
                [
                    'name' => 'roadrunner-1.9.0-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.9.0/roadrunner-1.9.0-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.9.0-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.9.0/roadrunner-1.9.0-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.9.0-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.9.0/roadrunner-1.9.0-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.9.0-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.9.0/roadrunner-1.9.0-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.9.0-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.9.0/roadrunner-1.9.0-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.8.4',
            'version' => 'v1.8.4',
            'assets' => [
                [
                    'name' => 'roadrunner-1.8.4-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.4/roadrunner-1.8.4-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.4-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.4/roadrunner-1.8.4-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.4-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.4/roadrunner-1.8.4-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.8.4-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.4/roadrunner-1.8.4-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.4-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.4/roadrunner-1.8.4-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.8.3',
            'version' => 'v1.8.3',
            'assets' => [
                [
                    'name' => 'roadrunner-1.8.3-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.3/roadrunner-1.8.3-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.3-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.3/roadrunner-1.8.3-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.3-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.3/roadrunner-1.8.3-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.8.3-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.3/roadrunner-1.8.3-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.3-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.3/roadrunner-1.8.3-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.8.2',
            'version' => 'v1.8.2',
            'assets' => [
                [
                    'name' => 'roadrunner-1.8.2-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.2/roadrunner-1.8.2-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.2-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.2/roadrunner-1.8.2-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.2-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.2/roadrunner-1.8.2-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.8.2-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.2/roadrunner-1.8.2-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.2-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.2/roadrunner-1.8.2-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.8.1',
            'version' => 'v1.8.1',
            'assets' => [
                [
                    'name' => 'roadrunner-1.8.1-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.1/roadrunner-1.8.1-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.1-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.1/roadrunner-1.8.1-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.1-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.1/roadrunner-1.8.1-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.8.1-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.1/roadrunner-1.8.1-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.1-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.1/roadrunner-1.8.1-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.8.0',
            'version' => 'v1.8.0',
            'assets' => [
                [
                    'name' => 'roadrunner-1.8.0-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.0/roadrunner-1.8.0-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.0-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.0/roadrunner-1.8.0-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.0-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.0/roadrunner-1.8.0-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.8.0-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.0/roadrunner-1.8.0-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.8.0-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.8.0/roadrunner-1.8.0-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.7.1',
            'version' => 'v1.7.1',
            'assets' => [
                [
                    'name' => 'roadrunner-1.7.1-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.7.1/roadrunner-1.7.1-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.7.1-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.7.1/roadrunner-1.7.1-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.7.1-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.7.1/roadrunner-1.7.1-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.7.1-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.7.1/roadrunner-1.7.1-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.7.1-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.7.1/roadrunner-1.7.1-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.7.0',
            'version' => 'v1.7.0',
            'assets' => [
                [
                    'name' => 'roadrunner-1.7.0-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.7.0/roadrunner-1.7.0-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.7.0-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.7.0/roadrunner-1.7.0-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.7.0-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.7.0/roadrunner-1.7.0-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.7.0-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.7.0/roadrunner-1.7.0-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.7.0-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.7.0/roadrunner-1.7.0-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.6.4',
            'version' => 'v1.6.4',
            'assets' => [
                [
                    'name' => 'roadrunner-1.6.4-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.4/roadrunner-1.6.4-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.4-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.4/roadrunner-1.6.4-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.4-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.4/roadrunner-1.6.4-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.6.4-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.4/roadrunner-1.6.4-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.4-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.4/roadrunner-1.6.4-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.6.3',
            'version' => 'v1.6.3',
            'assets' => [
                [
                    'name' => 'roadrunner-1.6.3-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.3/roadrunner-1.6.3-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.3-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.3/roadrunner-1.6.3-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.3-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.3/roadrunner-1.6.3-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.6.3-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.3/roadrunner-1.6.3-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.3-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.3/roadrunner-1.6.3-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.6.2',
            'version' => 'v1.6.2',
            'assets' => [
                [
                    'name' => 'roadrunner-1.6.2-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.2/roadrunner-1.6.2-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.2-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.2/roadrunner-1.6.2-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.2-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.2/roadrunner-1.6.2-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.6.2-unknown-musl-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.2/roadrunner-1.6.2-unknown-musl-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.2-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.2/roadrunner-1.6.2-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.6.1',
            'version' => 'v1.6.1',
            'assets' => [
                [
                    'name' => 'roadrunner-1.6.1-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.1/roadrunner-1.6.1-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.1-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.1/roadrunner-1.6.1-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.1-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.1/roadrunner-1.6.1-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.6.1-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.1/roadrunner-1.6.1-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.6.0',
            'version' => 'v1.6.0',
            'assets' => [
                [
                    'name' => 'roadrunner-1.6.0-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.0/roadrunner-1.6.0-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.0-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.0/roadrunner-1.6.0-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.6.0-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.0/roadrunner-1.6.0-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.6.0-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.6.0/roadrunner-1.6.0-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.5.3',
            'version' => 'v1.5.3',
            'assets' => [
                [
                    'name' => 'roadrunner-1.5.3-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.3/roadrunner-1.5.3-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.5.3-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.3/roadrunner-1.5.3-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.5.3-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.3/roadrunner-1.5.3-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.5.3-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.3/roadrunner-1.5.3-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.5.2',
            'version' => 'v1.5.2',
            'assets' => [
                [
                    'name' => 'roadrunner-1.5.2-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.2/roadrunner-1.5.2-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.5.2-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.2/roadrunner-1.5.2-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.5.2-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.2/roadrunner-1.5.2-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.5.2-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.2/roadrunner-1.5.2-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.5.1',
            'version' => 'v1.5.1',
            'assets' => [
                [
                    'name' => 'roadrunner-1.5.1-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.1/roadrunner-1.5.1-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.5.1-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.1/roadrunner-1.5.1-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.5.1-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.1/roadrunner-1.5.1-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.5.1-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.1/roadrunner-1.5.1-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.5.0',
            'version' => 'v1.5.0',
            'assets' => [
                [
                    'name' => 'roadrunner-1.5.0-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.0/roadrunner-1.5.0-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.5.0-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.0/roadrunner-1.5.0-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.5.0-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.0/roadrunner-1.5.0-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.5.0-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.5.0/roadrunner-1.5.0-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.4.8',
            'version' => 'v1.4.8',
            'assets' => [
                [
                    'name' => 'roadrunner-1.4.8-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.8/roadrunner-1.4.8-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.8-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.8/roadrunner-1.4.8-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.8-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.8/roadrunner-1.4.8-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.4.8-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.8/roadrunner-1.4.8-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.4.7',
            'version' => 'v1.4.7',
            'assets' => [
                [
                    'name' => 'roadrunner-1.4.7-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.7/roadrunner-1.4.7-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.7-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.7/roadrunner-1.4.7-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.7-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.7/roadrunner-1.4.7-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.4.7-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.7/roadrunner-1.4.7-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.4.6',
            'version' => 'v1.4.6',
            'assets' => [
                [
                    'name' => 'roadrunner-1.4.6-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.6/roadrunner-1.4.6-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.6-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.6/roadrunner-1.4.6-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.6-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.6/roadrunner-1.4.6-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.4.6-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.6/roadrunner-1.4.6-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.4.5',
            'version' => 'v1.4.5',
            'assets' => [
                [
                    'name' => 'roadrunner-1.4.5-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.5/roadrunner-1.4.5-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.5-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.5/roadrunner-1.4.5-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.5-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.5/roadrunner-1.4.5-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.4.5-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.5/roadrunner-1.4.5-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.4.4',
            'version' => 'v1.4.4',
            'assets' => [
                [
                    'name' => 'roadrunner-1.4.4-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.4/roadrunner-1.4.4-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.4-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.4/roadrunner-1.4.4-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.4-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.4/roadrunner-1.4.4-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.4.4-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.4/roadrunner-1.4.4-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.4.3',
            'version' => 'v1.4.3',
            'assets' => [
                [
                    'name' => 'roadrunner-1.4.3-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.3/roadrunner-1.4.3-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.3-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.3/roadrunner-1.4.3-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.3-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.3/roadrunner-1.4.3-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.4.3-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.3/roadrunner-1.4.3-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.4.2',
            'version' => 'v1.4.2',
            'assets' => [
                [
                    'name' => 'roadrunner-1.4.2-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.2/roadrunner-1.4.2-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.2-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.2/roadrunner-1.4.2-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.2-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.2/roadrunner-1.4.2-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.4.2-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.2/roadrunner-1.4.2-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.4.1',
            'version' => 'v1.4.1',
            'assets' => [
                [
                    'name' => 'roadrunner-1.4.1-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.1/roadrunner-1.4.1-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.1-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.1/roadrunner-1.4.1-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.1-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.1/roadrunner-1.4.1-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.4.1-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.1/roadrunner-1.4.1-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.4.0',
            'version' => 'v1.4.0',
            'assets' => [
                [
                    'name' => 'roadrunner-1.4.0-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.0/roadrunner-1.4.0-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.0-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.0/roadrunner-1.4.0-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.4.0-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.0/roadrunner-1.4.0-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.4.0-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.4.0/roadrunner-1.4.0-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.3.7',
            'version' => 'v1.3.7',
            'assets' => [
                [
                    'name' => 'roadrunner-1.3.7-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.7/roadrunner-1.3.7-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.7-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.7/roadrunner-1.3.7-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.7-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.7/roadrunner-1.3.7-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.3.7-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.7/roadrunner-1.3.7-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.3.6',
            'version' => 'v1.3.6',
            'assets' => [
                [
                    'name' => 'roadrunner-1.3.6-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.6/roadrunner-1.3.6-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.6-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.6/roadrunner-1.3.6-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.6-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.6/roadrunner-1.3.6-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.3.6-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.6/roadrunner-1.3.6-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.3.5',
            'version' => 'v1.3.5',
            'assets' => [
                [
                    'name' => 'roadrunner-1.3.5-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.5/roadrunner-1.3.5-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.5-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.5/roadrunner-1.3.5-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.5-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.5/roadrunner-1.3.5-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.3.5-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.5/roadrunner-1.3.5-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.3.4',
            'version' => 'v1.3.4',
            'assets' => [
                [
                    'name' => 'roadrunner-1.3.4-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.4/roadrunner-1.3.4-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.4-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.4/roadrunner-1.3.4-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.4-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.4/roadrunner-1.3.4-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.3.4-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.4/roadrunner-1.3.4-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.3.3',
            'version' => 'v1.3.3',
            'assets' => [
                [
                    'name' => 'roadrunner-1.3.3-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.3/roadrunner-1.3.3-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.3-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.3/roadrunner-1.3.3-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.3-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.3/roadrunner-1.3.3-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.3.3-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.3/roadrunner-1.3.3-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.3.2',
            'version' => 'v1.3.2',
            'assets' => [
                [
                    'name' => 'roadrunner-1.3.2-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.2/roadrunner-1.3.2-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.2-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.2/roadrunner-1.3.2-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.2-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.2/roadrunner-1.3.2-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.3.2-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.2/roadrunner-1.3.2-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.3.1',
            'version' => 'v1.3.1',
            'assets' => [
                [
                    'name' => 'roadrunner-1.3.1-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.1/roadrunner-1.3.1-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.1-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.1/roadrunner-1.3.1-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.1-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.1/roadrunner-1.3.1-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.3.1-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.1/roadrunner-1.3.1-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.3.0',
            'version' => 'v1.3.0',
            'assets' => [
                [
                    'name' => 'roadrunner-1.3.0-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.0/roadrunner-1.3.0-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.0-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.0/roadrunner-1.3.0-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.3.0-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.0/roadrunner-1.3.0-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.3.0-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.3.0/roadrunner-1.3.0-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.2.8',
            'version' => 'v1.2.8',
            'assets' => [
                [
                    'name' => 'roadrunner-1.2.8-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.8/roadrunner-1.2.8-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.8-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.8/roadrunner-1.2.8-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.8-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.8/roadrunner-1.2.8-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.2.8-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.8/roadrunner-1.2.8-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.2.7',
            'version' => 'v1.2.7',
            'assets' => [
                [
                    'name' => 'roadrunner-1.2.7-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.7/roadrunner-1.2.7-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.7-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.7/roadrunner-1.2.7-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.7-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.7/roadrunner-1.2.7-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.2.7-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.7/roadrunner-1.2.7-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.2.6',
            'version' => 'v1.2.6',
            'assets' => [
                [
                    'name' => 'roadrunner-1.2.6-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.6/roadrunner-1.2.6-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.6-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.6/roadrunner-1.2.6-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.6-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.6/roadrunner-1.2.6-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.2.6-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.6/roadrunner-1.2.6-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.2.5',
            'version' => 'v1.2.5',
            'assets' => [
                [
                    'name' => 'roadrunner-1.2.5-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.5/roadrunner-1.2.5-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.5-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.5/roadrunner-1.2.5-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.5-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.5/roadrunner-1.2.5-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.2.5-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.5/roadrunner-1.2.5-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.2.4',
            'version' => 'v1.2.4',
            'assets' => [
                [
                    'name' => 'roadrunner-1.2.4-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.4/roadrunner-1.2.4-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.4-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.4/roadrunner-1.2.4-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.4-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.4/roadrunner-1.2.4-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.2.4-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.4/roadrunner-1.2.4-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.2.3',
            'version' => 'v1.2.3',
            'assets' => [
                [
                    'name' => 'roadrunner-1.2.3-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.3/roadrunner-1.2.3-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.3-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.3/roadrunner-1.2.3-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.3-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.3/roadrunner-1.2.3-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.2.3-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.3/roadrunner-1.2.3-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.2.2',
            'version' => 'v1.2.2',
            'assets' => [
                [
                    'name' => 'roadrunner-1.2.2-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.2/roadrunner-1.2.2-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.2-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.2/roadrunner-1.2.2-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.2-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.2/roadrunner-1.2.2-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.2.2-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.2/roadrunner-1.2.2-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.2.1',
            'version' => 'v1.2.1',
            'assets' => [
                [
                    'name' => 'roadrunner-1.2.1-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.1/roadrunner-1.2.1-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.1-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.1/roadrunner-1.2.1-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.1-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.1/roadrunner-1.2.1-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.2.1-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.1/roadrunner-1.2.1-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.2.0',
            'version' => 'v1.2.0',
            'assets' => [
                [
                    'name' => 'roadrunner-1.2.0-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.0/roadrunner-1.2.0-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.0-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.0/roadrunner-1.2.0-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.2.0-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.0/roadrunner-1.2.0-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.2.0-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.2.0/roadrunner-1.2.0-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.1.1',
            'version' => 'v1.1.1',
            'assets' => [
                [
                    'name' => 'roadrunner-1.1.1-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.1.1/roadrunner-1.1.1-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.1.1-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.1.1/roadrunner-1.1.1-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.1.1-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.1.1/roadrunner-1.1.1-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.1.1-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.1.1/roadrunner-1.1.1-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.1.0',
            'version' => 'v1.1.0',
            'assets' => [
                [
                    'name' => 'roadrunner-1.1.0-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.1.0/roadrunner-1.1.0-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.1.0-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.1.0/roadrunner-1.1.0-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.1.0-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.1.0/roadrunner-1.1.0-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.1.0-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.1.0/roadrunner-1.1.0-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.0.5',
            'version' => 'v1.0.5',
            'assets' => [
                [
                    'name' => 'roadrunner-1.0.5-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.5/roadrunner-1.0.5-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.0.5-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.5/roadrunner-1.0.5-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.0.5-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.5/roadrunner-1.0.5-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.0.5-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.5/roadrunner-1.0.5-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.0.4',
            'version' => 'untagged-f7dbae972208eff54361',
            'assets' => [
                [
                    'name' => 'roadrunner-1.0.4-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/untagged-f7dbae972208eff54361/roadrunner-1.0.4-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.0.4-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/untagged-f7dbae972208eff54361/roadrunner-1.0.4-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.0.4-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/untagged-f7dbae972208eff54361/roadrunner-1.0.4-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.0.4-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/untagged-f7dbae972208eff54361/roadrunner-1.0.4-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.0.3',
            'version' => 'v1.0.3',
            'assets' => [
                [
                    'name' => 'roadrunner-1.0.3-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.3/roadrunner-1.0.3-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.0.3-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.3/roadrunner-1.0.3-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.0.3-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.3/roadrunner-1.0.3-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.0.3-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.3/roadrunner-1.0.3-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.0.2',
            'version' => 'v1.0.2',
            'assets' => [
                [
                    'name' => 'roadrunner-1.0.2-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.2/roadrunner-1.0.2-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.0.2-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.2/roadrunner-1.0.2-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.0.2-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.2/roadrunner-1.0.2-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.0.2-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.2/roadrunner-1.0.2-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.0.1',
            'version' => 'v1.0.1',
            'assets' => [
                [
                    'name' => 'roadrunner-1.0.1-darwin-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.1/roadrunner-1.0.1-darwin-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.0.1-freebsd-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.1/roadrunner-1.0.1-freebsd-amd64.zip',
                ],
                [
                    'name' => 'roadrunner-1.0.1-linux-amd64.tar.gz',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.1/roadrunner-1.0.1-linux-amd64.tar.gz',
                ],
                [
                    'name' => 'roadrunner-1.0.1-windows-amd64.zip',
                    'uri' => 'https://github.com/spiral/roadrunner/releases/download/v1.0.1/roadrunner-1.0.1-windows-amd64.zip',
                ],
            ],
        ],
        [
            'name' => '1.0.0',
            'version' => 'v1.0.0',
            'assets' => [],
        ],
        [
            'name' => '0.9.0',
            'version' => 'v0.9.0',
            'assets' => [],
        ],
    ];

    /**
     * @var array<ReleaseInterface>
     */
    private array $releases = [];

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface|null $client
     */
    public function __construct(HttpClientInterface $client = null)
    {
        $this->client = $client ?? HttpClient::create();

        foreach (self::RELEASES_DATA as $release) {
            $this->releases[] = $this->createRelease($release);
        }
    }

    /**
     * @param array{name: string, version: string, assets: array<array{name: string, uri: string}>} $release
     * @return ReleaseInterface
     */
    private function createRelease(array $release): ReleaseInterface
    {
        $assets = [];

        foreach ($release['assets'] as $asset) {
            $assets[] = $this->createAsset($asset);
        }

        return new GitHubRelease($this->client, $release['name'], $release['version'], $this->getName(), $assets);
    }

    /**
     * @param array{name: string, uri: string} $asset
     * @return AssetInterface
     */
    private function createAsset(array $asset): AssetInterface
    {
        return new GitHubAsset($this->client, $asset['name'], $asset['uri']);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'spiral/roadrunner';
    }

    /**
     * {@inheritDoc}
     */
    public function getReleases(): ReleasesCollection
    {
        return new ReleasesCollection($this->releases);
    }
}

