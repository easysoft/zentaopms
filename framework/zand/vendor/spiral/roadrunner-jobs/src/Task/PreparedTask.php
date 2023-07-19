<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs\Task;

use Spiral\RoadRunner\Jobs\Options;
use Spiral\RoadRunner\Jobs\OptionsInterface;

/**
 * @psalm-suppress MissingImmutableAnnotation QueuedTask class is mutable.
 */
final class PreparedTask extends Task implements PreparedTaskInterface
{
    use WritableHeadersTrait;

    /**
     * @var OptionsInterface
     */
    private OptionsInterface $options;

    /**
     * @param non-empty-string $name
     * @param array $payload
     * @param OptionsInterface|null $options
     */
    public function __construct(string $name, array $payload, OptionsInterface $options = null)
    {
        $this->options = $options ?? new Options();

        parent::__construct($name, $payload);
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->options = clone $this->options;
    }

    /**
     * @return OptionsInterface
     */
    public function getOptions(): OptionsInterface
    {
        return $this->options;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function withValue($value, $name = null): self
    {
        $name ??= $this->getPayloadNextIndex();
        assert(\is_string($name) || \is_int($name), 'Precondition [name is string|int] failed');

        $self = clone $this;
        $self->payload[$name] = $value;

        return $self;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function withoutValue($name): self
    {
        assert(\is_string($name) || \is_int($name), 'Precondition [name is string|int] failed');

        $self = clone $this;
        unset($self->payload[$name]);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getDelay(): int
    {
        return $this->options->getDelay();
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
        $self->options = Options::from($this->options)
            ->withDelay($seconds)
        ;

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority(): int
    {
        return $this->options->getPriority();
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function withPriority(int $priority): self
    {
        assert($priority >= 0, 'Precondition [priority >= 0] failed');

        $self = clone $this;
        $self->options = Options::from($this->options)
            ->withPriority($priority)
        ;

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoAck(): bool
    {
        return $this->options->getAutoAck();
    }

    /**
     * {@inheritDoc}
     */
    public function withAutoAck(bool $autoAck): self
    {
        $self = clone $this;
        $self->options = Options::from($this->options)
            ->withAutoAck($autoAck)
        ;

        return $self;
    }

    /**
     * @return int
     */
    private function getPayloadNextIndex(): int
    {
        /** @var array<int> $indices */
        $indices = \array_filter(\array_keys($this->getPayload()), '\\is_int');

        if ($indices === []) {
            return 0;
        }

        return \max($indices) + 1;
    }
}
