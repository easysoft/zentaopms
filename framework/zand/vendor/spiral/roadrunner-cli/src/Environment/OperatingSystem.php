<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Environment;

use JetBrains\PhpStorm\ExpectedValues;
use Spiral\RoadRunner\Console\Environment\OperatingSystem\Factory;

/**
 * @psalm-type OperatingSystemType = OperatingSystem::OS_*
 */
final class OperatingSystem
{
    /**
     * @var string
     */
    public const OS_DARWIN = 'darwin';

    /**
     * @var string
     */
    public const OS_BSD = 'freebsd';

    /**
     * @var string
     */
    public const OS_LINUX = 'linux';

    /**
     * @var string
     */
    public const OS_WINDOWS = 'windows';

    /**
     * @var string
     */
    public const OS_ALPINE = 'unknown-musl';

    /**
     * @param array|null $variables
     * @return OperatingSystemType
     */
    #[ExpectedValues(valuesFromClass: OperatingSystem::class)]
    public static function createFromGlobals(array $variables = null): string
    {
        return (new Factory())->createFromGlobals($variables);
    }


    /**
     * @param string $value
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return \in_array($value, self::all(), true);
    }

    /**
     * @return array<string, OperatingSystemType>
     */
    public static function all(): array
    {
        static $values;

        if ($values === null) {
            $values = Enum::values(self::class, 'OS_');
        }

        /** @psalm-var array<string, OperatingSystemType> $values */
        return $values;
    }
}
