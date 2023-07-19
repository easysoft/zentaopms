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
 * @mixin ProvidesHeadersInterface
 * @psalm-require-implements ProvidesHeadersInterface
 * @psalm-immutable
 */
trait HeadersTrait
{
    /**
     * @var array<non-empty-string, array<string>>
     */
    protected array $headers = [];

    /**
     * {@inheritDoc}
     *
     * @psalm-return array<non-empty-string, array<string>>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name Header field name.
     * @psalm-return bool
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]) && \count($this->headers[$name]) > 0;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name
     * @psalm-return array<string>
     */
    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-param non-empty-string $name
     * @psalm-return string
     */
    public function getHeaderLine(string $name): string
    {
        return \implode(',', $this->getHeader($name));
    }
}
