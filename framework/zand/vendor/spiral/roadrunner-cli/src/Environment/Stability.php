<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Environment;

/**
 * @psalm-type StabilityType = Stability::STABILITY_*
 */
final class Stability
{
    /**
     * @var string
     */
    public const STABILITY_STABLE = 'stable';

    /**
     * @var string
     */
    public const STABILITY_RC = 'RC';

    /**
     * @var string
     */
    public const STABILITY_BETA = 'beta';

    /**
     * @var string
     */
    public const STABILITY_ALPHA = 'alpha';

    /**
     * @var string
     */
    public const STABILITY_DEV = 'dev';

    /**
     * @var int[]
     */
    private const WEIGHT = [
        self::STABILITY_STABLE => 4,
        self::STABILITY_RC     => 3,
        self::STABILITY_BETA   => 2,
        self::STABILITY_ALPHA  => 1,
        self::STABILITY_DEV    => 0,
    ];

    /**
     * @param StabilityType $type
     * @return positive-int|0
     */
    public static function toInt(string $type): int
    {
        return self::WEIGHT[$type] ?? 0;
    }

    /**
     * @return array<string, StabilityType>
     */
    public static function all(): array
    {
        static $values;

        if ($values === null) {
            $values = Enum::values(self::class, 'STABILITY_');
        }

        /** @psalm-var array<string, StabilityType> $values */
        return $values;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return \in_array($value, self::all(), true);
    }
}
