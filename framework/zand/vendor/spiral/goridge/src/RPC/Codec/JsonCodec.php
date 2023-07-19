<?php

declare(strict_types=1);

namespace Spiral\Goridge\RPC\Codec;

use Spiral\Goridge\Frame;
use Spiral\Goridge\RPC\CodecInterface;
use Spiral\Goridge\RPC\Exception\CodecException;

final class JsonCodec implements CodecInterface
{
    /**
     * {@inheritDoc}
     */
    public function getIndex(): int
    {
        return Frame::CODEC_JSON;
    }

    /**
     * {@inheritDoc}
     */
    public function encode($payload): string
    {
        try {
            $result = \json_encode($payload, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new CodecException(\sprintf('Json encode: %s', $e->getMessage()), (int)$e->getCode(), $e);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function decode(string $payload, $options = null)
    {
        try {
            $flags = \JSON_THROW_ON_ERROR;

            if (\is_int($options)) {
                $flags |= $options;
            }

            return \json_decode($payload, true, 512, $flags);
        } catch (\JsonException $e) {
            throw new CodecException(\sprintf('Json decode: %s', $e->getMessage()), (int)$e->getCode(), $e);
        }
    }
}
