<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs\Task;

/**
 * @psalm-immutable
 * @psalm-allow-private-mutation
 */
class QueuedTask extends Task implements QueuedTaskInterface
{
    /**
     * @var non-empty-string
     */
    protected string $id;

    /**
     * @var non-empty-string
     */
    protected string $queue;

    /**
     * @param non-empty-string $id
     * @param non-empty-string $queue
     * @param non-empty-string $name
     * @param array $payload
     * @param array<non-empty-string, array<string>> $headers
     */
    public function __construct(string $id, string $queue, string $name, array $payload = [], array $headers = [])
    {
        $this->id = $id;
        $this->queue = $queue;

        parent::__construct($name, $payload, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueue(): string
    {
        return $this->queue;
    }
}
