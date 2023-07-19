<?php

declare(strict_types=1);

namespace Spiral\Goridge\RPC\Codec;

use Spiral\Goridge\Frame;
use Spiral\Goridge\RPC\CodecInterface;
use Spiral\Goridge\RPC\Exception\CodecException;

final class RawCodec implements CodecInterface
{
    /**
     * {@inheritDoc}
     */
    public function getIndex(): int
    {
        return Frame::CODEC_RAW;
    }

    /**
     * {@inheritDoc}
     */
    public function encode($payload): string
    {
        if (!is_string($payload)) {
            throw new CodecException(
                sprintf('Only string payloads can be send using RawCodec, %s given', gettype($payload))
            );
        }

        return $payload;
    }

    /**
     * {@inheritDoc}
     */
    public function decode(string $payload, $options = null)
    {
        return $payload;
    }
}
