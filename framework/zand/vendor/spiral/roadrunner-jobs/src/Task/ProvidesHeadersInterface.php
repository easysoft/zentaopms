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
 * An interface that provides API to the headers contained in the task.
 *
 * The capabilities of this interface repeat those in the implementation of
 * PSR-6 MessageInterface.
 */
interface ProvidesHeadersInterface
{
    /**
     * Returns list of the headers.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     * <code>
     *  foreach ($task->getHeaders() as $name => $values) {
     *      echo $name . ': ' . implode(', ', $values) . "\n";
     *  }
     * </code>
     *
     * <code>
     *  // Example with output of all headers
     *  foreach ($task->getHeaders() as $name => $values) {
     *      foreach ($values as $value) {
     *          echo $name . ': ' . $value . "\n";
     *      }
     *  }
     * </code>
     *
     * @psalm-mutation-free
     * @return array<non-empty-string, array<string>>
     */
    public function getHeaders(): array;

    /**
     * Checks if a header exists by the given name.
     *
     * @psalm-mutation-free
     * @param non-empty-string $name Header field name.
     * @return bool Returns {@see true} if any header names match the given
     *              header name by string comparison. Returns {@see false} if
     *              no matching header name is found in the message.
     */
    public function hasHeader(string $name): bool;

    /**
     * Retrieves the task's header value by the given name.
     *
     * This method returns an array of all the header values of the given
     * header name.
     *
     * If the header does not appear in the task, this method MUST return an
     * empty array.
     *
     * @psalm-mutation-free
     * @param non-empty-string $name
     * @return array<string>
     */
    public function getHeader(string $name): array;

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * This method returns all the header values of the given header name as a
     * string concatenated together using a comma (",").
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation. For such headers, use {@see getHeader()} instead
     * and supply your own delimiter when concatenating.
     *
     * If the header does not appear in the message, this method MUST return
     * an empty string.
     *
     * @psalm-mutation-free
     * @param non-empty-string $name
     * @return string
     */
    public function getHeaderLine(string $name): string;
}
