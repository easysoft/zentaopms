<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs\Task;

use Spiral\RoadRunner\Jobs\OptionsInterface;

interface PreparedTaskInterface extends
    TaskInterface,
    OptionsInterface,
    WritableHeadersInterface,
    MutatesDelayInterface
{
    /**
     * Adds additional data to the task.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated payload data.
     *
     * See {@see getPayload()} to retrieve information about the current value.
     *
     * @psalm-mutation-free
     * @param mixed $value Passed payload data
     * @param array-key|null $name Optional payload data's name (key)
     * @return static
     */
    public function withValue($value, $name = null): self;

    /**
     * Excludes payload data from task by given key (name).
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated payload data.
     *
     * See {@see getPayload()} to retrieve information about the current value.
     *
     * @psalm-mutation-free
     * @param array-key $name
     * @return static
     */
    public function withoutValue($name): self;
}
