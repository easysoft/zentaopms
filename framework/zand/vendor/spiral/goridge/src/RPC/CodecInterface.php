<?php

declare(strict_types=1);

namespace Spiral\Goridge\RPC;

use Spiral\Goridge\RPC\Exception\CodecException;

/**
 * Serializes incoming and deserializes received messages.
 */
interface CodecInterface
{
    /**
     * Coded index, uniquely identified by remote server.
     *
     * @return int
     */
    public function getIndex(): int;

    /**
     * @param mixed $payload
     * @return string
     * @throws CodecException
     */
    public function encode($payload): string;

    /**
     * @param string $payload
     * @param mixed|null $options
     * @return mixed
     * @throws CodecException
     */
    public function decode(string $payload, $options = null);
}
