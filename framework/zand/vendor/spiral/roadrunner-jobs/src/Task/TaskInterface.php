<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs\Task;

interface TaskInterface extends ProvidesHeadersInterface
{
    /**
     * Returns the (non-empty) name of the task/job.
     *
     * @psalm-mutation-free
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Returns payload of the task/job.
     *
     * @psalm-mutation-free
     * @return array
     */
    public function getPayload(): array;

    /**
     * Retrieves value from payload by its key.
     *
     * @psalm-mutation-free
     * @param array-key $key
     * @param mixed $default
     * @return mixed
     */
    public function getValue($key, $default = null);

    /**
     * Determines that key defined in the payload.
     *
     * @psalm-mutation-free
     * @param array-key $key
     * @return bool
     */
    public function hasValue($key): bool;
}
