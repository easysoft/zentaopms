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
 * @psalm-type DriverType = Driver::*
 */
interface Driver
{
    /**
     * In-memory builtin RoadRunner driver.
     *
     * @psalm-var DriverType
     * @var string
     */
    public const MEMORY = 'memory';

    /**
     * AMQP-based queue server implementation.
     *
     * @link https://www.rabbitmq.com/
     * @link http://activemq.apache.org/
     * @link http://qpid.apache.org/
     *
     * @psalm-var DriverType
     * @var string
     */
    public const AMQP = 'amqp';

    /**
     * @psalm-var DriverType
     * @var string
     */
    public const BEANSTALK = 'beanstalk';

    /**
     * @psalm-var DriverType
     * @var string
     */
    public const SQS = 'sqs';

    /**
     * @internal NOT Available: Reserved for future use.
     *
     * @psalm-var DriverType
     * @var string
     */
    public const REDIS = 'redis';

    /**
     * @psalm-var DriverType
     * @var string
     */
    public const NATS = 'nats';

    /**
     * @internal NOT Available: Reserved for future use.
     *
     * @psalm-var DriverType
     * @var string
     */
    public const NSQ = 'nsq';
}
