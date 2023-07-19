<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs;

use Spiral\Goridge\RPC\Codec\ProtobufCodec;
use Spiral\Goridge\RPC\RPC;
use Spiral\Goridge\RPC\RPCInterface;
use Spiral\RoadRunner\Environment;
use Spiral\RoadRunner\Jobs\DTO\V1\Pipelines;
use Spiral\RoadRunner\Jobs\DTO\V1\Stat;
use Spiral\RoadRunner\Jobs\DTO\V1\Stats;
use Spiral\RoadRunner\Jobs\Exception\JobsException;
use Spiral\RoadRunner\Jobs\Queue\Pipeline;
use Spiral\RoadRunner\Jobs\Serializer\JsonSerializer;
use Spiral\RoadRunner\Jobs\Serializer\SerializerAwareInterface;
use Spiral\RoadRunner\Jobs\Serializer\SerializerInterface;
use Spiral\RoadRunner\Jobs\Task\PreparedTask;
use Spiral\RoadRunner\Jobs\Task\PreparedTaskInterface;
use Spiral\RoadRunner\Jobs\Task\QueuedTaskInterface;

final class Queue implements QueueInterface, SerializerAwareInterface
{
    /**
     * @var Options
     */
    private Options $options;

    /**
     * @var non-empty-string
     */
    private string $name;

    /**
     * @var Pipeline
     */
    private Pipeline $pipeline;

    /**
     * @var RPCInterface
     */
    private RPCInterface $rpc;

    /**
     * @param non-empty-string $name
     * @param RPCInterface|null $rpc
     * @param SerializerInterface|null $serializer
     */
    public function __construct(string $name, RPCInterface $rpc = null, SerializerInterface $serializer = null)
    {
        assert($name !== '', 'Precondition [name !== ""] failed');

        $this->rpc = ($rpc ?? $this->createRPCConnection())
            ->withCodec(new ProtobufCodec())
        ;

        $this->pipeline = new Pipeline($this, $this->rpc, $serializer ?? new JsonSerializer());

        $this->name = $name;
        $this->options = new Options();
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->options = clone $this->options;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->pipeline->getSerializer();
    }

    /**
     * {@inheritDoc}
     */
    public function withSerializer(SerializerInterface $serializer): SerializerAwareInterface
    {
        $self = clone $this;
        $self->pipeline = $this->pipeline->withSerializer($serializer);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultOptions(): OptionsInterface
    {
        return $this->options;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function withDefaultOptions(?OptionsInterface $options = null): self
    {
        $self = clone $this;
        /** @psalm-suppress PropertyTypeCoercion */
        $self->options = $options ?? new Options();

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function create(string $name, array $payload = [], OptionsInterface $options = null): PreparedTaskInterface
    {
        $options = Options::from($this->options)
            ->mergeOptional($options)
        ;

        return new PreparedTask($name, $payload, $options);
    }

    /**
     * Creates a nre task and push it into specified queue.
     *
     * This method exists for compatibility with version RoadRunner 1.x.
     *
     * @param non-empty-string $name
     * @param array $payload
     * @param OptionsInterface|null $options
     * @return QueuedTaskInterface
     * @throws JobsException
     */
    public function push(string $name, array $payload = [], OptionsInterface $options = null): QueuedTaskInterface
    {
        return $this->dispatch(
            $this->create($name, $payload, $options)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(PreparedTaskInterface $task): QueuedTaskInterface
    {
        return $this->pipeline->send($task);
    }

    /**
     * {@inheritDoc}
     */
    public function dispatchMany(PreparedTaskInterface ...$tasks): iterable
    {
        return $this->pipeline->sendMany($tasks);
    }

    /**
     * {@inheritDoc}
     */
    public function pause(): void
    {
        try {
            $this->rpc->call('jobs.Pause', new Pipelines([
                'pipelines' => [$this->getName()],
            ]));
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function resume(): void
    {
        try {
            $this->rpc->call('jobs.Resume', new Pipelines([
                'pipelines' => [$this->getName()],
            ]));
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isPaused(): bool
    {
        $stat = $this->getPipelineStat();

        return $stat !== null && ! $stat->getReady();
    }

    private function createRPCConnection(): RPCInterface
    {
        $env = Environment::fromGlobals();

        return RPC::create($env->getRPCAddress());
    }

    public function getPipelineStat(): ?Stat
    {
        try {
            /** @var Stats $stats */
            $stats = $this->rpc->call('jobs.Stat', '', Stats::class);
        } catch (\Throwable $e) {
            throw new JobsException($e->getMessage(), (int)$e->getCode(), $e);
        }

        /** @var Stat $stat */
        foreach ($stats->getStats() as $stat) {
            if ($stat->getPipeline() === $this->name) {
                return $stat;
            }
        }

        return null;
    }
}
