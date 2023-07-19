<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs;

use Spiral\RoadRunner\Jobs\Exception\JobsException;
use Spiral\RoadRunner\Jobs\Queue\CreateInfoInterface;

/**
 * Aggregate interface that provides methods for working with the queue server.
 *
 * @template-extends \IteratorAggregate<string, QueueInterface>
 */
interface JobsInterface extends \IteratorAggregate, \Countable
{
    /**
     * The method returns information about the availability of the queue server.
     *
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * A method that returns the selected queue. As the first argument, you
     * need to pass the name of a specific queue.
     *
     * @param non-empty-string $queue
     * @return QueueInterface
     */
    public function connect(string $queue): QueueInterface;

    /**
     * A method that creates an arbitrary queue. The first argument should be
     * information about the queue being created.
     *
     * @param CreateInfoInterface $info
     * @return QueueInterface
     * @throws JobsException
     */
    public function create(CreateInfoInterface $info): QueueInterface;

    /**
     * A method that pauses an arbitrary number of queues whose names must be
     * specified as method arguments.
     *
     * @param QueueInterface|non-empty-string $queue
     * @param QueueInterface|non-empty-string ...$queues
     * @throws JobsException
     */
    public function pause($queue, ...$queues): void;

    /**
     * A method that resumes (unpauses) an arbitrary number of queues whose
     * names must be specified as method arguments.
     *
     * @param QueueInterface|non-empty-string $queue
     * @param QueueInterface|non-empty-string ...$queues
     * @throws JobsException
     */
    public function resume($queue, ...$queues): void;
}
