<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Http;

use Generator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use Spiral\RoadRunner\WorkerInterface;
use Stringable;

/**
 * Manages PSR-7 request and response.
 *
 * @psalm-import-type UploadedFile from Request
 * @psalm-import-type UploadedFilesList from Request
 */
class PSR7Worker implements PSR7WorkerInterface
{
    /**
     * @var int Preferred chunk size for streaming output.
     *      if not greater than 0, then streaming response is turned off
     */
    public int $chunkSize = 0;

    /**
     * @var HttpWorker
     */
    private HttpWorker $httpWorker;

    /**
     * @var ServerRequestFactoryInterface
     */
    private ServerRequestFactoryInterface $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    private StreamFactoryInterface $streamFactory;

    /**
     * @var UploadedFileFactoryInterface
     */
    private UploadedFileFactoryInterface $uploadsFactory;

    /**
     * @var array
     */
    private array $originalServer;

    /**
     * @var string[] Valid values for HTTP protocol version
     */
    private static array $allowedVersions = ['1.0', '1.1', '2'];

    /**
     * @param WorkerInterface $worker
     * @param ServerRequestFactoryInterface $requestFactory
     * @param StreamFactoryInterface $streamFactory
     * @param UploadedFileFactoryInterface $uploadsFactory
     */
    public function __construct(
        WorkerInterface $worker,
        ServerRequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        UploadedFileFactoryInterface $uploadsFactory
    ) {
        $this->httpWorker = new HttpWorker($worker);
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->uploadsFactory = $uploadsFactory;
        $this->originalServer = $_SERVER;
    }

    /**
     * @return WorkerInterface
     */
    public function getWorker(): WorkerInterface
    {
        return $this->httpWorker->getWorker();
    }

    /**
     * @return ServerRequestInterface|null
     * @throws \JsonException
     */
    public function waitRequest(): ?ServerRequestInterface
    {
        $httpRequest = $this->httpWorker->waitRequest();
        if ($httpRequest === null) {
            return null;
        }

        $_SERVER = $this->configureServer($httpRequest);

        return $this->mapRequest($httpRequest, $_SERVER);
    }

    /**
     * Send response to the application server.
     *
     * @param ResponseInterface $response
     * @throws \JsonException
     */
    public function respond(ResponseInterface $response): void
    {
        if ($this->chunkSize > 0) {
            $this->httpWorker->respondStream(
                $response->getStatusCode(),
                $this->streamToGenerator($response->getBody()),
                $response->getHeaders()
            );
        } else {
            $this->httpWorker->respond(
                $response->getStatusCode(),
                (string)$response->getBody(),
                $response->getHeaders());
        }
    }

    /**
     * @return Generator<mixed, scalar|Stringable, mixed, Stringable|scalar|null> Compatible
     *         with {@see \Spiral\RoadRunner\Http\HttpWorker::respondStream()}.
     */
    private function streamToGenerator(StreamInterface $stream): Generator
    {
        $stream->rewind();
        $size = $stream->getSize();
        if ($size !== null && $size < $this->chunkSize) {
            return (string)$stream;
        }
        $sum = 0;
        while (!$stream->eof()) {
            if ($size === null) {
                $chunk = $stream->read($this->chunkSize);
            } else {
                $left = $size - $sum;
                $chunk = $stream->read(\min($this->chunkSize, $left));
                if ($left <= $this->chunkSize && \strlen($chunk) === $left) {
                    return $chunk;
                }
            }
            $sum += \strlen($chunk);
            yield $chunk;
        }
    }

    /**
     * Returns altered copy of _SERVER variable. Sets ip-address,
     * request-time and other values.
     *
     * @param Request $request
     * @return non-empty-array<array-key|string, mixed|string>
     */
    protected function configureServer(Request $request): array
    {
        $server = $this->originalServer;

        $server['REQUEST_URI'] = $request->uri;
        $server['REQUEST_TIME'] = $this->timeInt();
        $server['REQUEST_TIME_FLOAT'] = $this->timeFloat();
        $server['REMOTE_ADDR'] = $request->getRemoteAddr();
        $server['REQUEST_METHOD'] = $request->method;

        $server['HTTP_USER_AGENT'] = '';
        foreach ($request->headers as $key => $value) {
            $key = \strtoupper(\str_replace('-', '_', (string)$key));
            if (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH'])) {
                $server[$key] = \implode(', ', $value);
            } else {
                $server['HTTP_' . $key] = \implode(', ', $value);
            }
        }

        return $server;
    }

    /**
     * @return int
     */
    protected function timeInt(): int
    {
        return \time();
    }

    /**
     * @return float
     */
    protected function timeFloat(): float
    {
        return \microtime(true);
    }

    /**
     * @param Request $httpRequest
     * @param array $server
     * @return ServerRequestInterface
     * @throws \JsonException
     */
    protected function mapRequest(Request $httpRequest, array $server): ServerRequestInterface
    {
        $request = $this->requestFactory->createServerRequest(
            $httpRequest->method,
            $httpRequest->uri,
            $server
        );

        $request = $request
            ->withProtocolVersion(static::fetchProtocolVersion($httpRequest->protocol))
            ->withCookieParams($httpRequest->cookies)
            ->withQueryParams($httpRequest->query)
            ->withUploadedFiles($this->wrapUploads($httpRequest->uploads))
        ;

        /** @psalm-suppress MixedAssignment */
        foreach ($httpRequest->attributes as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        foreach ($httpRequest->headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if ($httpRequest->parsed) {
            $request = $request->withParsedBody($httpRequest->getParsedBody());
        }

        if ($httpRequest->body !== '') {
            return $request->withBody($this->streamFactory->createStream($httpRequest->body));
        }

        return $request;
    }

    /**
     * Wraps all uploaded files with UploadedFile.
     *
     * @param UploadedFilesList $files
     * @return UploadedFileInterface[]|mixed[]
     */
    protected function wrapUploads(array $files): array
    {
        $result = [];

        foreach ($files as $index => $file) {
            if (! isset($file['name'])) {
                /** @psalm-var UploadedFilesList $file */
                $result[$index] = $this->wrapUploads($file);
                continue;
            }

            if (\UPLOAD_ERR_OK === $file['error']) {
                $stream = $this->streamFactory->createStreamFromFile($file['tmpName']);
            } else {
                $stream = $this->streamFactory->createStream();
            }

            $result[$index] = $this->uploadsFactory->createUploadedFile(
                $stream,
                $file['size'],
                $file['error'],
                $file['name'],
                $file['mime']
            );
        }

        return $result;
    }

    /**
     * Normalize HTTP protocol version to valid values
     *
     * @param string $version
     * @return string
     */
    private static function fetchProtocolVersion(string $version): string
    {
        $v = \substr($version, 5);

        if ($v === '2.0') {
            return '2';
        }

        // Fallback for values outside of valid protocol versions
        if (! \in_array($v, static::$allowedVersions, true)) {
            return '1.1';
        }

        return $v;
    }
}
