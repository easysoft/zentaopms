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
 * The DTO to create the NATS driver.
 *
 * @psalm-import-type CreateInfoArrayType from CreateInfoInterface
 */
final class NatsCreateInfo extends CreateInfo
{
    /**
     * @var positive-int
     */
    public const PREFETCH_DEFAULT_VALUE = 100;

    /**
     * @var bool
     */
    public const DELIVER_NEW_DEFAULT_VALUE = true;

    /**
     * @var positive-int
     */
    public const RATE_LIMIT_DEFAULT_VALUE = 100;

    /**
     * @var bool
     */
    public const DELETE_STREAM_ON_STOP_DEFAULT_VALUE = false;

    /**
     * @var bool
     */
    public const DELETE_AFTER_ACK_DEFAULT_VALUE = false;

    /**
     * @var positive-int
     */
    public const PRIORITY_DEFAULT_VALUE = 2;

    /**
     * @var positive-int
     */
    public int $prefetch = self::PREFETCH_DEFAULT_VALUE;

    /**
     * @var non-empty-string
     */
    public string $stream;

    /**
     * @var non-empty-string
     */
    public string $subject;

    /**
     * @var bool
     */
    public bool $deliverNew = self::DELIVER_NEW_DEFAULT_VALUE;

    /**
     * @var positive-int
     */
    public int $rateLimit = self::RATE_LIMIT_DEFAULT_VALUE;

    /**
     * @var bool
     */
    public bool $deleteStreamOnStop = self::DELETE_STREAM_ON_STOP_DEFAULT_VALUE;

    /**
     * @var bool
     */
    public bool $deleteAfterAck = self::DELETE_AFTER_ACK_DEFAULT_VALUE;

    /**
     * @var positive-int
     */
    public int $priority = self::PRIORITY_DEFAULT_VALUE;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $subject
     * @param non-empty-string $stream
     * @param positive-int $priority
     * @param positive-int $prefetch
     * @param bool $deliverNew
     * @param positive-int $rateLimit
     * @param bool $deleteStreamOnStop
     * @param bool $deleteAfterAck
     */
    public function __construct(
        string $name,
        string $subject,
        string $stream,
        int $priority = self::PRIORITY_DEFAULT_VALUE,
        int $prefetch = self::PREFETCH_DEFAULT_VALUE,
        bool $deliverNew = self::DELIVER_NEW_DEFAULT_VALUE,
        int $rateLimit = self::RATE_LIMIT_DEFAULT_VALUE,
        bool $deleteStreamOnStop = self::DELETE_STREAM_ON_STOP_DEFAULT_VALUE,
        bool $deleteAfterAck = self::DELETE_AFTER_ACK_DEFAULT_VALUE
    ) {
        parent::__construct(Driver::NATS, $name, $priority);

        assert($prefetch >= 1, 'Precondition [prefetch >= 1] failed');
        assert($rateLimit >= 1, 'Precondition [rateLimit >= 1] failed');
        assert($subject !== '', 'Precondition [subject !== ""] failed');
        assert($stream !== '', 'Precondition [stream !== ""] failed');

        $this->stream = $stream;
        $this->prefetch = $prefetch;
        $this->subject = $subject;
        $this->deliverNew = $deliverNew;
        $this->rateLimit = $rateLimit;
        $this->deleteStreamOnStop = $deleteStreamOnStop;
        $this->deleteAfterAck = $deleteAfterAck;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return \array_merge(parent::toArray(), [
            'prefetch'              => $this->prefetch,
            'subject'               => $this->subject,
            'deliver_new'           => $this->deliverNew,
            'rate_limit'            => $this->rateLimit,
            'stream'                => $this->stream,
            'delete_stream_on_stop' => $this->deleteStreamOnStop,
            'delete_after_ack'      => $this->deleteAfterAck,
        ]);
    }
}
