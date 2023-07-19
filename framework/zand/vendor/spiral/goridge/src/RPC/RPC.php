<?php

declare(strict_types=1);

namespace Spiral\Goridge\RPC;

use Spiral\Goridge\Frame;
use Spiral\Goridge\Relay;
use Spiral\Goridge\RelayInterface;
use Spiral\Goridge\RPC\Codec\JsonCodec;
use Spiral\Goridge\RPC\Exception\RPCException;
use Spiral\Goridge\RPC\Exception\ServiceException;
use Spiral\RoadRunner\Environment;
use Spiral\RoadRunner\EnvironmentInterface;

class RPC implements RPCInterface
{
    /**
     * @var RelayInterface
     */
    private RelayInterface $relay;

    /**
     * @var CodecInterface
     */
    private CodecInterface $codec;

    /**
     * @var string|null
     */
    private ?string $service = null;

    /**
     * @var positive-int
     */
    private static int $seq = 1;

    /**
     * @param RelayInterface $relay
     * @param CodecInterface|null $codec
     */
    public function __construct(RelayInterface $relay, CodecInterface $codec = null)
    {
        $this->relay = $relay;
        $this->codec = $codec ?? new JsonCodec();
    }

    /**
     * {@inheritDoc}
     * @psalm-pure
     */
    public function withServicePrefix(string $service): RPCInterface
    {
        /** @psalm-suppress ImpureVariable */
        $rpc = clone $this;
        $rpc->service = $service;

        return $rpc;
    }

    /**
     * {@inheritDoc}
     * @psalm-pure
     */
    public function withCodec(CodecInterface $codec): RPCInterface
    {
        /** @psalm-suppress ImpureVariable */
        $rpc = clone $this;
        $rpc->codec = $codec;

        return $rpc;
    }

    /**
     * {@inheritDoc}
     */
    public function call(string $method, $payload, $options = null)
    {
        $this->relay->send($this->packFrame($method, $payload));

        // wait for the frame confirmation
        $frame = $this->relay->waitFrame();

        if (\count($frame->options) !== 2) {
            throw new RPCException('Invalid RPC frame, options missing');
        }

        if ($frame->options[0] !== self::$seq) {
            throw new RPCException('Invalid RPC frame, sequence mismatch');
        }

        self::$seq++;

        return $this->decodeResponse($frame, $options);
    }

    /**
     * @param string $connection
     * @param CodecInterface|null $codec
     * @return RPCInterface
     */
    public static function create(string $connection, CodecInterface $codec = null): RPCInterface
    {
        $relay = Relay::create($connection);

        return new self($relay, $codec);
    }

    /**
     * @param EnvironmentInterface $env
     * @param CodecInterface|null $codec
     * @return RPCInterface
     *
     * @psalm-suppress UndefinedClass
     */
    public static function fromEnvironment(EnvironmentInterface $env, CodecInterface $codec = null): RPCInterface
    {
        /** @var string $address */
        $address = $env->getRPCAddress();
        return self::create($address, $codec);
    }

    /**
     * @param CodecInterface|null $codec
     * @return RPCInterface
     *
     * @psalm-suppress UndefinedClass
     */
    public static function fromGlobals(CodecInterface $codec = null): RPCInterface
    {
        /** @var EnvironmentInterface $env */
        $env = Environment::fromGlobals();
        return self::fromEnvironment($env, $codec);
    }

    /**
     * @param Frame $frame
     * @param mixed|null $options
     * @return mixed
     *
     * @throws Exception\ServiceException
     */
    private function decodeResponse(Frame $frame, $options = null)
    {
        // exclude method name
        $body = \substr((string)$frame->payload, $frame->options[1]);

        if ($frame->hasFlag(Frame::ERROR)) {
            $name = $this->relay instanceof \Stringable ? (string)$this->relay : \get_class($this->relay);

            throw new ServiceException(\sprintf("Error '%s' on %s", $body, $name));
        }

        return $this->codec->decode($body, $options);
    }

    /**
     * @param string $method
     * @param mixed $payload
     * @return Frame
     */
    private function packFrame(string $method, $payload): Frame
    {
        if ($this->service !== null) {
            $method = $this->service . '.' . \ucfirst($method);
        }

        $body = $method . $this->codec->encode($payload);
        return new Frame($body, [self::$seq, \strlen($method)], $this->codec->getIndex());
    }
}
