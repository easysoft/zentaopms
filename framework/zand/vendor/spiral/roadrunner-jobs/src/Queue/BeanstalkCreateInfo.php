<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs\Queue;

/**
 * The DTO to create the Beanstalk driver.
 *
 * @psalm-import-type CreateInfoArrayType from CreateInfoInterface
 */
final class BeanstalkCreateInfo extends CreateInfo
{
    /**
     * @var positive-int
     */
    public const TUBE_PRIORITY_DEFAULT_VALUE = 10;

    /**
     * @var positive-int
     */
    public const TUBE_PRIORITY_MAX_VALUE = 2 ** 32;

    /**
     * @var non-empty-string
     */
    public const TUBE_DEFAULT_VALUE = 'default';

    /**
     * @var positive-int|0
     */
    public const RESERVE_TIMEOUT_DEFAULT_VALUE = 5;

    /**
     * @var positive-int
     */
    public int $tubePriority = self::TUBE_PRIORITY_DEFAULT_VALUE;

    /**
     * @var non-empty-string
     */
    public string $tube = self::TUBE_DEFAULT_VALUE;

    /**
     * @var positive-int|0
     */
    public int $reserveTimeout = self::RESERVE_TIMEOUT_DEFAULT_VALUE;

    /**
     * @param non-empty-string $name
     * @param positive-int $priority
     * @param positive-int $tubePriority
     * @param non-empty-string $tube
     * @param positive-int|0 $reserveTimeout
     */
    public function __construct(
        string $name,
        int $priority = self::PRIORITY_DEFAULT_VALUE,
        int $tubePriority = self::TUBE_PRIORITY_DEFAULT_VALUE,
        string $tube = self::TUBE_DEFAULT_VALUE,
        int $reserveTimeout = self::RESERVE_TIMEOUT_DEFAULT_VALUE
    ) {
        parent::__construct(Driver::BEANSTALK, $name, $priority);

        assert($tubePriority >= 1, 'Precondition [tubePriority >= 1] failed');
        assert($tube !== '', 'Precondition [tube !== ""] failed');
        assert($reserveTimeout >= 0, 'Precondition [reserveTimeout >= 0] failed');

        $this->tubePriority = $tubePriority;
        $this->tube = $tube;
        $this->reserveTimeout = $reserveTimeout;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return \array_merge(parent::toArray(), [
            'tube_priority'   => $this->tubePriority,
            'tube'            => $this->tube,
            'reserve_timeout' => $this->reserveTimeout,
        ]);
    }
}
