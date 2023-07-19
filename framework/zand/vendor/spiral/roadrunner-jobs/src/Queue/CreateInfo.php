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
 * @psalm-import-type DriverType from Driver
 * @psalm-import-type CreateInfoArrayType from CreateInfoInterface
 */
class CreateInfo implements CreateInfoInterface
{
    /**
     * @var positive-int
     */
    public const PRIORITY_DEFAULT_VALUE = 10;

    /**
     * @var non-empty-string
     */
    public string $name;

    /**
     * @var DriverType
     */
    public string $driver;

    /**
     * Queue default priority for for each task pushed into this queue if the
     * priority value for these tasks was not explicitly set.
     *
     * @var positive-int
     */
    public int $priority = self::PRIORITY_DEFAULT_VALUE;

    /**
     * @param DriverType $driver
     * @param non-empty-string $name
     * @param positive-int $priority
     */
    public function __construct(string $driver, string $name, int $priority = self::PRIORITY_DEFAULT_VALUE)
    {
        assert($driver !== '', 'Precondition [driver !== ""] failed');
        assert($name !== '', 'Precondition [name !== ""] failed');
        assert($priority >= 1, 'Precondition [priority >= 1] failed');

        $this->driver = $driver;
        $this->name = $name;
        $this->priority = $priority;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'driver' => $this->driver,
            'priority' => $this->priority,
        ];
    }
}
