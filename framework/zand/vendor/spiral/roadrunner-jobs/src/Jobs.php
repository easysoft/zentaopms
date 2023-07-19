<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs;

use Spiral\Goridge\RPC\Codec\JsonCodec;
use Spiral\Goridge\RPC\Codec\ProtobufCodec;
use Spiral\Goridge\RPC\RPC;
use Spiral\Goridge\RPC\RPCInterface;
use Spiral\RoadRunner\Environment;
use Spiral\RoadRunner\Jobs\DTO\V1\DeclareRequest;
use Spiral\RoadRunner\Jobs\DTO\V1\Pipelines;
use Spiral\RoadRunner\Jobs\Exception\JobsException;
use Spiral\RoadRunner\Jobs\Queue\CreateInfoInterface;
use Spiral\RoadRunner\Jobs\Serializer\JsonSerializer;
use Spiral\RoadRunner\Jobs\Serializer\SerializerAwareInterface;
use Spiral\RoadRunner\Jobs\Serializer\SerializerInterface;

final class Jobs implements JobsInterface, SerializerAwareInterface
{
    /**
     * @var RPCInterface
     */
    private RPCInterface $rpc;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param RPCInterface|null $rpc
     * @param SerializerInterface|null $serializer
     */
    public function __construct(RPCInterface $rpc = null, SerializerInterface $serializer = null)
    {
        $this->rpc = ($rpc ?? $this->createRPCConnection())
            ->withCodec(new ProtobufCodec());

        $this->serializer = $serializer ?? new JsonSerializer();
    }

    /**
     * {@inheritDoc}
     */
    public function create(CreateInfoInterface $info): QueueInterface
    {
        try {
            $this->rpc->call('jobs.Declare', new DeclareRequest([
                'pipeline' => $this->toStringOfStringMap($info->toArray()),
            ]));

            return $this->connect($info->getName());
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * @param array<string, mixed> $map
     * @return array<string, string>
     * @throws \Throwable
     * @psalm-suppress MixedAssignment
     */
    private function toStringOfStringMap(array $map): array
    {
        $marshalled = [];

        foreach ($map as $key => $value) {
            switch (true) {
                case \is_int($value):
                case \is_string($value):
                case $value instanceof \Stringable:
                // PHP 7.4 additional assertion
                case \is_object($value) && \method_exists($value, '__toString'):
                    $marshalled[$key] = (string)$value;
                    break;

                case \is_bool($value):
                    $marshalled[$key] = $value ? 'true' : 'false';
                    break;

                case \is_array($value):
                    $marshalled[$key] = (string)\json_encode($value, \JSON_THROW_ON_ERROR);
                    break;

                default:
                    throw new \InvalidArgumentException(
                        \sprintf('Can not cast to string unrecognized value of type %s', \get_debug_type($value))
                    );
            }
        }

        return $marshalled;
    }

    /**
     * {@inheritDoc}
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function withSerializer(SerializerInterface $serializer): SerializerAwareInterface
    {
        $self = clone $this;
        $self->serializer = $serializer;

        return $self;
    }

    /**
     * @param string $queue
     * @return QueueInterface
     */
    public function connect(string $queue): QueueInterface
    {
        assert($queue !== '', 'Precondition [queue !== ""] failed');

        return new Queue($queue, $this->rpc, $this->serializer);
    }

    /**
     * {@inheritDoc}
     */
    public function isAvailable(): bool
    {
        try {
            /** @var array<string>|mixed $result */
            $result = $this->rpc
                ->withCodec(new JsonCodec())
                ->call('informer.List', true);

            if (!\is_array($result)) {
                return false;
            }

            return \in_array('jobs', $result, true);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function pause($queue, ...$queues): void
    {
        try {
            $this->rpc->call('jobs.Pause', new Pipelines([
                'pipelines' => $this->names($queue, ...$queues),
            ]));
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function resume($queue, ...$queues): void
    {
        try {
            $this->rpc->call('jobs.Resume', new Pipelines([
                'pipelines' => $this->names($queue, ...$queues),
            ]));
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     * @throws JobsException
     */
    public function getIterator(): \Traversable
    {
        try {
            /** @var Pipelines $result */
            $result = $this->rpc->call('jobs.List', '', Pipelines::class);

            /** @var string $queue */
            foreach ($result->getPipelines() as $queue) {
                yield $queue => $this->connect($queue);
            }
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     * @throws JobsException
     */
    public function count(): int
    {
        return \iterator_count($this->getIterator());
    }

    /**
     * @return RPCInterface
     */
    private function createRPCConnection(): RPCInterface
    {
        $env = Environment::fromGlobals();

        return RPC::create($env->getRPCAddress());
    }

    /**
     * @param QueueInterface|non-empty-string ...$queues
     * @return array<non-empty-string>
     */
    private function names(...$queues): array
    {
        $names = [];

        foreach ($queues as $queue) {
            assert(
                $queue instanceof QueueInterface || \is_string($queue),
                'Queue should be an instance of ' . QueueInterface::class .
                ' or type of string, but ' . \get_debug_type($queue) . ' passed'
            );

            if ($queue instanceof QueueInterface) {
                $queue = $queue->getName();
            }

            $names[] = $queue;
        }

        return $names;
    }
}
