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
 * @mixin WritableHeadersInterface
 * @psalm-require-implements WritableHeadersInterface
 * @psalm-immutable
 */
trait WritableHeadersTrait
{
    use HeadersTrait;

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string|iterable<non-empty-string> $value
     * @psalm-return static
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function withHeader(string $name, $value): self
    {
        assert($name !== '', 'Precondition [name !== ""] failed');

        $value = \is_iterable($value) ? $value : [$value];

        $self = clone $this;
        $self->headers[$name] = [];

        foreach ($value as $item) {
            $self->headers[$name][] = $item;
        }

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string|iterable<non-empty-string> $value
     * @psalm-return static
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function withAddedHeader(string $name, $value): self
    {
        assert($name !== '', 'Precondition [name !== ""] failed');

        /** @var iterable<non-empty-string> $value */
        $value = \is_iterable($value) ? $value : [$value];

        /** @var array<non-empty-string> $headers */
        $headers = $this->headers[$name] ?? [];

        foreach ($value as $item) {
            $headers[] = $item;
        }

        return $this->withHeader($name, $headers);
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name
     * @psalm-return static
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function withoutHeader(string $name): self
    {
        assert($name !== '', 'Precondition [name !== ""] failed');

        if (!isset($this->headers[$name])) {
            return $this;
        }

        $self = clone $this;
        unset($self->headers[$name]);
        return $self;
    }
}
