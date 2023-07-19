<?php

declare(strict_types=1);

namespace Spiral\Goridge;

use JetBrains\PhpStorm\ExpectedValues;
use Socket;
use Spiral\Goridge\Exception\HeaderException;
use Spiral\Goridge\Exception\InvalidArgumentException;
use Spiral\Goridge\Exception\RelayException;
use Spiral\Goridge\Exception\TransportException;

/**
 * Communicates with remote server/client over be-directional socket using byte payload:
 *
 * [ prefix       ][ payload                               ]
 * [ 1+8+8 bytes  ][ message length|LE ][message length|BE ]
 *
 * prefix:
 * [ flag       ][ message length, unsigned int 64bits, LittleEndian ]
 *
 * @psalm-type SocketRelayType = SocketRelay::SOCK_*
 * @psalm-type PortType = positive-int|0|null
 *
 * @psalm-suppress DeprecatedInterface
 */
class SocketRelay extends Relay implements StringableRelayInterface
{
    /**#@+
     * Supported socket types.
     */
    public const SOCK_TCP  = 0;
    public const SOCK_UNIX = 1;
    /**#@-*/

    /**
     * @var positive-int|0
     */
    public const RECONNECT_RETRIES = 10;

    /**
     * @var positive-int|0
     */
    public const RECONNECT_TIMEOUT = 100;

    /**
     * 1) Pathname to "sock" file in case of UNIX socket
     * 2) URI string in case of TCP socket
     */
    private string $address;

    /**
     * @var PortType
     */
    private ?int $port;

    /**
     * @var SocketRelayType
     */
    private int $type;

    /**
     * @var Socket|resource|null
     */
    private $socket = null;

    /**
     * Example:
     *
     * <code>
     *  $relay = new SocketRelay("localhost", 7000);
     *  $relay = new SocketRelay("/tmp/rpc.sock", null, Socket::UNIX_SOCKET);
     * </code>
     *
     * @param string          $address Localhost, ip address or hostname.
     * @param PortType        $port    Ignored for UNIX sockets.
     * @param SocketRelayType $type    Default: TCP_SOCKET
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $address,
        ?int $port = null,
        #[ExpectedValues(valuesFromClass: SocketRelay::class)]
        int $type = self::SOCK_TCP
    ) {
        // Guaranteed at the level of composer's json config
        assert(\extension_loaded('sockets'));

        switch ($type) {
            case self::SOCK_TCP:
                // TCP address should always be in lowercase
                $address = \strtolower($address);

                if ($port === null) {
                    throw new InvalidArgumentException(\sprintf("Тo port given for TPC socket on '%s'", $address));
                }

                if ($port < 0 || $port > 65535) {
                    throw new InvalidArgumentException(\sprintf("Invalid port given for TPC socket on '%s'", $address));
                }

                break;

            case self::SOCK_UNIX:
                $port = null;
                break;

            default:
                throw new InvalidArgumentException(\sprintf("Undefined connection type %s on '%s'", $type, $address));
        }

        $this->address = $address;
        $this->port = $port;
        $this->type = $type;
    }

    /**
     * Destruct connection and disconnect.
     */
    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->close();
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->type === self::SOCK_TCP) {
            return "tcp://{$this->address}:{$this->port}";
        }

        return "unix://{$this->address}";
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->socket !== null;
    }

    /**
     * @return Frame
     * @throws RelayException
     * @psalm-suppress PossiblyNullArgument Reason: Using the "connect()" method guarantees
     *                                      the existence of the socket.
     */
    public function waitFrame(): Frame
    {
        $this->connect();

        $header = '';
        /** @psalm-suppress PossiblyInvalidArgument Reason: PHP 7-8 compatibility */
        $headerLength = \socket_recv($this->socket, $header, 12, \MSG_WAITALL);

        if ($headerLength !== 12) {
            /** @psalm-suppress PossiblyInvalidArgument Reason: PHP 7-8 compatibility */
            $error = \socket_strerror(\socket_last_error($this->socket));
            throw new HeaderException(\sprintf('Unable to read frame header: %s', $error));
        }

        $parts = Frame::readHeader($header);

        // total payload length
        $payload = '';
        $length = $parts[1] * 4 + $parts[2];

        while ($length > 0) {
            /** @psalm-suppress PossiblyInvalidArgument Reason: PHP 7-8 compatibility */
            $bufferLength = \socket_recv($this->socket, $buffer, $length, \MSG_WAITALL);

            /**
             * Suppress "buffer === null" assertion, because buffer can contain
             * NULL in case of socket_recv function error.
             *
             * @psalm-suppress TypeDoesNotContainNull
             */
            if ($bufferLength === false || $buffer === null) {
                /** @psalm-suppress PossiblyInvalidArgument Reason: PHP 7-8 compatibility */
                $message = \socket_strerror(\socket_last_error($this->socket));
                throw new HeaderException(\sprintf('Unable to read payload from socket: %s', $message));
            }

            $payload .= $buffer;
            $length -= $bufferLength;
        }

        return Frame::initFrame($parts, $payload);
    }

    /**
     * @param Frame $frame
     * @psalm-suppress PossiblyNullArgument Reason: Using the "connect()" method guarantees
     *                                      the existence of the socket.
     */
    public function send(Frame $frame): void
    {
        $this->connect();

        $body = Frame::packFrame($frame);

        /** @psalm-suppress PossiblyInvalidArgument Reason: PHP 7-8 compatibility */
        if (\socket_send($this->socket, $body, \strlen($body), 0) === false) {
            throw new TransportException('Unable to write payload to the stream');
        }
    }

    /**
     * Ensure socket connection. Returns true if socket successfully connected
     * or have already been connected.
     *
     * @param positive-int|0 $retries Count of connection tries.
     * @param positive-int|0 $timeout Timeout between reconnections in microseconds.
     * @return bool
     * @throws RelayException
     * @throws \Error When sockets are used in unsupported environment.
     */
    public function connect(int $retries = self::RECONNECT_RETRIES, int $timeout = self::RECONNECT_TIMEOUT): bool
    {
        assert($retries >= 1);
        assert($timeout > 0);

        if ($this->isConnected()) {
            return true;
        }

        $socket = $this->createSocket();

        if ($socket === false) {
            throw new RelayException("Unable to create socket {$this}");
        }

        try {
            $status = false;

            for ($attempt = 0; $attempt <= $retries; ++$attempt) {
                /** @psalm-suppress PossiblyInvalidArgument Reason: PHP 7-8 compatibility */
                if ($status = @\socket_connect($socket, $this->address, $this->port ?? 0)) {
                    break;
                }

                \usleep(\max(0, $timeout));
            }

            if ($status === false) {
                /** @psalm-suppress PossiblyInvalidArgument Reason: PHP 7-8 compatibility */
                throw new RelayException(\socket_strerror(\socket_last_error($socket)));
            }
        } catch (\Throwable $e) {
            throw new RelayException("Unable to establish connection {$this}", 0, $e);
        }

        $this->socket = $socket;

        return true;
    }

    /**
     * Close connection.
     *
     * @throws RelayException
     * @psalm-suppress PossiblyNullArgument Reason: Using the "isConnected()" assertion guarantees
     *                                      the existence of the socket.
     */
    public function close(): void
    {
        if (! $this->isConnected()) {
            throw new RelayException("Unable to close socket '{$this}', socket already closed");
        }

        /** @psalm-suppress PossiblyInvalidArgument Reason: PHP 7-8 compatibility */
        \socket_close($this->socket);
        $this->socket = null;
    }

    /**
     * @return Socket|resource|false
     */
    private function createSocket()
    {
        if ($this->type === self::SOCK_UNIX) {
            return \socket_create(\AF_UNIX, \SOCK_STREAM, 0);
        }

        return \socket_create(\AF_INET, \SOCK_STREAM, \SOL_TCP);
    }
}
