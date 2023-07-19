<?php

declare(strict_types=1);

namespace Spiral\Goridge\RPC\Codec;

use MessagePack\MessagePack;
use Spiral\Goridge\Frame;
use Spiral\Goridge\RPC\CodecInterface;

/**
 * @psalm-type PackHandler = \Closure(mixed): string
 * @psalm-type UnpackHandler = \Closure(string, mixed|null): mixed
 */
final class MsgpackCodec implements CodecInterface
{
    /**
     * @var PackHandler
     * @psalm-suppress PropertyNotSetInConstructor Reason: Initialized via {@see initPacker()}
     */
    private \Closure $pack;

    /**
     * @var UnpackHandler
     * @psalm-suppress PropertyNotSetInConstructor Reason: Initialized via {@see initPacker()}
     */
    private \Closure $unpack;

    /**
     * Constructs extension using native or fallback implementation.
     */
    public function __construct()
    {
        $this->initPacker();
    }

    /**
     * {@inheritDoc}
     */
    public function getIndex(): int
    {
        return Frame::CODEC_MSGPACK;
    }

    /**
     * {@inheritDoc}
     */
    public function encode($payload): string
    {
        return ($this->pack)($payload);
    }

    /**
     * {@inheritDoc}
     */
    public function decode(string $payload, $options = null)
    {
        return ($this->unpack)($payload, $options);
    }

    /**
     * Init pack and unpack functions.
     *
     * @psalm-suppress MixedArgument
     */
    private function initPacker(): void
    {
        // Is native extension supported
        if (\function_exists('msgpack_pack') && \function_exists('msgpack_unpack')) {
            $this->pack = static function ($payload): string {
                return msgpack_pack($payload);
            };

            $this->unpack = static function (string $payload, $options = null) {
                if ($options !== null) {
                    return msgpack_unpack($payload, $options);
                }

                return msgpack_unpack($payload);
            };

            return;
        }

        // Is composer's library supported
        if (\class_exists(MessagePack::class)) {
            $this->pack = static function ($payload): string {
                return MessagePack::pack($payload);
            };

            $this->unpack = static function (string $payload, $options = null) {
                return MessagePack::unpack($payload, $options);
            };
        }

        throw new \LogicException('Could not initialize codec, please install msgpack extension or library');
    }
}
