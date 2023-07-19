<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class Payload
{
    /**
     * Execution payload (binary).
     *
     * @psalm-readonly
     * @var string
     */
    public string $body = '';

    /**
     * Execution context (binary).
     *
     * @psalm-readonly
     */
    public string $header = '';

    /**
     * End of stream.
     * The {@see true} value means the Payload block is last in the stream.
     *
     * @psalm-readonly
     */
    public bool $eos = true;

    public function __construct(?string $body, ?string $header = null, bool $eos = true)
    {
        $this->body = $body ?? '';
        $this->header = $header ?? '';
        $this->eos = $eos;
    }
}
