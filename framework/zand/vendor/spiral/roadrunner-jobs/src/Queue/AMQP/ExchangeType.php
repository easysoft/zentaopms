<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs\Queue\AMQP;

/**
 * The enum that represents the type of task delivery.
 *
 * @psalm-type ExchangeTypeEnum = ExchangeType::TYPE_*
 */
interface ExchangeType
{
    /**
     * Used when a task needs to be delivered to specific queues. The task is
     * published to an exchanger with a specific routing key and goes to all
     * queues that are associated with this exchanger with a similar routing
     * key.
     *
     * @var string
     * @psalm-var ExchangeTypeEnum
     */
    public const TYPE_DIRECT = 'direct';

    /**
     * Similarly by {@see ExchangeType::TYPE_DIRECT} exchange enables selective
     * routing by comparing the routing key. But, in this case, the key is set
     * using a template, like "user.*.messages".
     *
     * @var string
     * @psalm-var ExchangeTypeEnum
     */
    public const TYPE_TOPICS = 'topics';

    /**
     * Routes tasks to related queues based on a comparison of the (key, value)
     * pairs of the headers property of the binding and the similar property of
     * the message.
     *
     * @var string
     * @psalm-var ExchangeTypeEnum
     */
    public const TYPE_HEADERS = 'headers';

    /**
     * All tasks are delivered to all queues even if a routing key is specified
     * in the task.
     *
     * @var string
     * @psalm-var ExchangeTypeEnum
     */
    public const TYPE_FANOUT = 'fanout';
}
