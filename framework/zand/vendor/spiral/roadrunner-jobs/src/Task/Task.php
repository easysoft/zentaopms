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
abstract class Task implements TaskInterface
{
    use HeadersTrait;

    /**
     * @var non-empty-string
     */
    protected string $name;

    /**
     * @var array
     */
    protected array $payload = [];

    /**
     * @param non-empty-string $name
     * @param array $payload
     * @param array<non-empty-string, array<string>> $headers
     */
    public function __construct(string $name, array $payload = [], array $headers = [])
    {
        assert($name !== '', 'Precondition [job !== ""] failed');

        $this->name = $name;
        $this->payload = $payload;
        $this->headers = $headers;
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
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($key, $default = null)
    {
        // Note: The following code "$this->payload[$key] ?? $default" will
        // work faster, but it will not work correctly if the key contains
        // a NULL value.
        return $this->hasValue($key) ? $this->payload[$key] : $default;
    }

    /**
     * {@inheritDoc}
     */
    public function hasValue($key): bool
    {
        // Array lookup optimization: Op ISSET_ISEMPTY_VAR faster than direct
        // array_key_exists function execution.
        return isset($this->payload[$key]) || \array_key_exists($key, $this->payload);
    }
}
