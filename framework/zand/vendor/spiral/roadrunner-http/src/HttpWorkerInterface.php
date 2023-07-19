<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Http;

use Spiral\RoadRunner\WorkerAwareInterface;

/**
 * @psalm-import-type HeadersList from Request
 */
interface HttpWorkerInterface extends WorkerAwareInterface
{
    /**
     * Wait for incoming http request.
     *
     * @return Request|null
     */
    public function waitRequest(): ?Request;

    /**
     * Send response to the application server.
     *
     * @param int               $status  Http status code
     * @param string            $body    Body of response
     * @param HeadersList|array $headers An associative array of the message's headers. Each key MUST be a header name,
     *                                   and each value MUST be an array of strings for that header.
     */
    public function respond(int $status, string $body, array $headers = []): void;
}
