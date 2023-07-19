<?php

declare(strict_types=1);

namespace Spiral\Goridge\RPC;

use Spiral\Goridge\Exception\GoridgeException;
use Spiral\Goridge\RPC\Exception\RPCException;

interface RPCInterface
{
    /**
     * Create RPC instance with service specific prefix.
     *
     * @psalm-pure
     * @param string $service
     * @return RPCInterface
     */
    public function withServicePrefix(string $service): self;

    /**
     * Create RPC instance with service specific codec.
     *
     * @psalm-pure
     * @param CodecInterface $codec
     * @return RPCInterface
     */
    public function withCodec(CodecInterface $codec): self;

    /**
     * Invoke remove RoadRunner service method using given payload (free form).
     *
     * @param string $method
     * @param mixed $payload
     * @param mixed|null $options
     * @return mixed
     * @throws GoridgeException
     * @throws RPCException
     */
    public function call(string $method, $payload, $options = null);
}
