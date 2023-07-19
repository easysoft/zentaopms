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
 * An interface that provides a set of methods that allows to change the
 * headers of the task.
 *
 * The capabilities of this interface repeat those in the implementation of
 * PSR-6 MessageInterface.
 */
interface WritableHeadersInterface extends ProvidesHeadersInterface
{
    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated header and value.
     *
     * See also {@see getHeaders()}, {@see getHeader()} or {@see hasHeader()}
     * to retrieve information about the current value.
     *
     * @psalm-mutation-free
     * @param non-empty-string $name Header field name.
     * @param non-empty-string|iterable<non-empty-string> $value Header value(s).
     * @return static
     */
    public function withHeader(string $name, $value): self;

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new header and/or value.
     *
     * See also {@see getHeaders()}, {@see getHeader()} or {@see hasHeader()}
     * to retrieve information about the current value.
     *
     * @psalm-mutation-free
     * @param non-empty-string $name Header field name to add.
     * @param non-empty-string|iterable<non-empty-string> $value Header value(s).
     * @return static
     */
    public function withAddedHeader(string $name, $value): self;

    /**
     * Return an instance without the specified header.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the named header.
     *
     * See also {@see getHeaders()}, {@see getHeader()} or {@see hasHeader()}
     * to retrieve information about the current value.
     *
     * @psalm-mutation-free
     * @param non-empty-string $name Header field name to remove.
     * @return static
     */
    public function withoutHeader(string $name): self;
}
