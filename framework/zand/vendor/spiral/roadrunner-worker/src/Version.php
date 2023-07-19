<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner;

use Composer\InstalledVersions;

final class Version
{
    /**
     * @var string[]
     */
    public const PACKAGE_NAMES = [
        'spiral/roadrunner',
        'spiral/roadrunner-worker',
    ];

    /**
     * @var string
     */
    public const VERSION_FALLBACK = 'dev-master';

    /**
     * @return string
     */
    public static function current(): string
    {
        foreach (self::PACKAGE_NAMES as $name) {
            if (InstalledVersions::isInstalled($name)) {
                return \ltrim((string)InstalledVersions::getPrettyVersion($name), 'v');
            }
        }

        return self::VERSION_FALLBACK;
    }

    /**
     * @return string
     */
    public static function constraint(): string
    {
        $current = self::current();

        if (\str_contains($current, '.')) {
            [$major] = \explode('.', $current);

            return \is_numeric($major) ? "$major.*" : '*';
        }

        return '*';
    }
}
