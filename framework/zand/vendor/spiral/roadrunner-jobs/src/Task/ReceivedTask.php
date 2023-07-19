<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs\Task;

use Spiral\RoadRunner\Jobs\Exception\JobsException;
use Spiral\RoadRunner\Jobs\Exception\SerializationException;
use Spiral\RoadRunner\Payload;
use Spiral\RoadRunner\WorkerInterface;

/**
 * @psalm-type SuccessData = array
 * @psalm-type ErrorData = array { message: string, requeue: bool, delay_seconds: positive-int|0 }
 *
 * @psalm-import-type TypeEnum from Type
 *
 * @psalm-suppress MissingImmutableAnnotation The implementation of this task is mutable.
 */
final class ReceivedTask extends QueuedTask implements ReceivedTaskInterface
{
    use WritableHeadersTrait;

    /**
     * @var WorkerInterface
     */
    private WorkerInterface $worker;

    /**
     * @var TypeEnum|null
     */
    private ?int $completed = null;

    /**
     * @var positive-int|0
     */
    private int $delay = 0;

    /**
     * @param WorkerInterface $worker
     * @param non-empty-string $id
     * @param non-empty-string $queue
     * @param non-empty-string $job
     * @param array $payload
     * @param array<non-empty-string, array<string>> $headers
     */
    public function __construct(
        WorkerInterface $worker,
        string $id,
        string $queue,
        string $job,
        array $payload = [],
        array $headers = []
    ) {
        $this->worker = $worker;

        parent::__construct($id, $queue, $job, $payload, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function complete(): void
    {
        $this->respond(Type::SUCCESS);
    }

    /**
     * @param TypeEnum $type
     * @param SuccessData|ErrorData $data
     * @return void
     * @throws JobsException
     */
    private function respond(int $type, array $data = []): void
    {
        if ($this->completed === null) {
            try {
                $body = \json_encode(['type' => $type, 'data' => $data], \JSON_THROW_ON_ERROR);

                $this->worker->respond(new Payload($body));
            } catch (\JsonException $e) {
                throw new SerializationException($e->getMessage(), (int)$e->getCode(), $e);
            } catch (\Throwable $e) {
                throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
            }

            $this->completed = $type;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function fail($error, bool $requeue = false): void
    {
        assert(
            // PHP 8.0+: Is string or Stringable
            \is_string($error) || $error instanceof \Stringable
            // PHP 7.4 or lower: Is Throwable (may be false-positive static analysis alert)
            //                   or contains __toString() (PHP 7.4 or lower).
            || $error instanceof \Throwable || (\is_object($error) && \method_exists($error, '__toString')),
            'Precondition [error is string|Stringable|Throwable] failed'
        );

        $data = [
            'message'       => (string)$error,
            'requeue'       => $requeue,
            'delay_seconds' => $this->delay,
        ];

        if (!empty($this->headers)) {
            $data['headers'] = $this->headers;
        }

        $this->respond(Type::ERROR, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function isCompleted(): bool
    {
        return $this->completed !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccessful(): bool
    {
        return $this->completed === Type::SUCCESS;
    }

    /**
     * {@inheritDoc}
     */
    public function isFails(): bool
    {
        return $this->completed === Type::ERROR;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function withDelay(int $seconds): self
    {
        assert($seconds >= 0, 'Precondition [seconds >= 0] failed');

        $self = clone $this;
        $self->delay = $seconds;

        return $self;
    }
}
