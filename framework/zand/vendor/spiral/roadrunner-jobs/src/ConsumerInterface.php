<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs;

use Spiral\RoadRunner\Jobs\Task\ReceivedTaskInterface;

/**
 * An interface that contains all the required methods of the consumer: The
 * handler of all incoming messages from the RoadRunner.
 */
interface ConsumerInterface
{
    /**
     * A method that blocks the current execution thread and waits for
     * incoming tasks.
     *
     * <code>
     *  while($task = $consumer->waitTask()) {
     *     // Do something with received $task
     *     var_dump($task);
     *  }
     * </code>
     *
     * @return ReceivedTaskInterface|null
     */
    public function waitTask(): ?ReceivedTaskInterface;
}
