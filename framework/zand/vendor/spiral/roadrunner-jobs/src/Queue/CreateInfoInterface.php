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
 * The generic interface meaning that each implementation is valid for creating
 * new queues.
 *
 * @psalm-import-type DriverType from Driver
 *
 * @psalm-type CreateInfoArrayType = array {
 *  name: non-empty-string,
 *  driver: DriverType,
 *  priority: positive-int
 * }
 */
interface CreateInfoInterface
{
    /**
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * @return DriverType
     */
    public function getDriver(): string;

    /**
     * When transferring to the internal RPC method of creating queues, the data
     * must be represented in the form of a Map<string, string> type, which can
     * be represented as PHP array<non-empty-string, non-empty-string>.
     *
     * This method returns all available settings in the queues in the specified
     * format.
     *
     * @return CreateInfoArrayType
     */
    public function toArray(): array;
}
