<?php
declare(strict_types=1);

use Nyholm\Psr7\Stream;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\{ResponseInterface, StreamInterface};

/**
 * Roadrunner的response类。
 * Response class for RoadRunner.
 *
 * @package zand
 */
class zandResponse implements ResponseInterface
{
    private array $headers = array();

    private array $headerNames = array();

    private bool $sent = false;

    public string $cookieDomain = '';

    public string $cookiePath = '/';

    public int $statusCode = 200;

    public string $reasonPhrase = '';

    private $protocol = '1.1';

    public Stream $stream;

    public bool $cookieSecure = false;

    private const PHRASES = array(
        100 => 'Continue', 101 => 'Switching Protocols', 102 => 'Processing',
        200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 207 => 'Multi-status', 208 => 'Already Reported',
        300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 306 => 'Switch Proxy', 307 => 'Temporary Redirect',
        400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Time-out', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Large', 415 => 'Unsupported Media Type', 416 => 'Requested range not satisfiable', 417 => 'Expectation Failed', 418 => 'I\'m a teapot', 422 => 'Unprocessable Entity', 423 => 'Locked', 424 => 'Failed Dependency', 425 => 'Unordered Collection', 426 => 'Upgrade Required', 428 => 'Precondition Required', 429 => 'Too Many Requests', 431 => 'Request Header Fields Too Large', 451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Time-out', 505 => 'HTTP Version not supported', 506 => 'Variant Also Negotiates', 507 => 'Insufficient Storage', 508 => 'Loop Detected', 511 => 'Network Authentication Required',
    );

    public function __construct()
    {
        $this->stream = Stream::create('');
    }

    public function getProtocolVersion(): string
    {
        return $this->protocol;
    }

    public function withProtocolVersion($version): MessageInterface
    {
        if (!is_scalar($version)) {
            throw new InvalidArgumentException('Protocol version must be a string');
        }

        if ($this->protocol === $version) {
            return $this;
        }

        $new = clone $this;
        $new->protocol = (string) $version;

        return $new;
    }

    public function cleanup(): void
    {
        $this->headers      = array();
        $this->statusCode   = 200;
        $this->sent         = false;
        $this->reasonPhrase = '';
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeaderLine($header): string
    {
        return implode(', ', $this->getHeader($header));
    }

    public function setHeader(string $name, ?string $value = null): static
    {
        $this->headers[$name] = [$value];
        return $this;
    }

    public function addHeader(string $name, string $value): static
    {
        $this->headers[$name][] = $value;
        return $this;
    }

    public function hasHeader($header): bool
    {
        return isset($this->headerNames[strtr($header, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')]);
    }

    public function withHeader($header, $value): MessageInterface
    {
        $normalized = strtr($header, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');

        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }
        $new->headerNames[$normalized] = $header;
        $new->headers[$header] = $value;

        return $new;
    }

    public function withAddedHeader($header, $value): MessageInterface
    {
        if (!is_string($header) || '' === $header) {
            throw new InvalidArgumentException('Header name must be an RFC 7230 compatible string');
        }

        $new = clone $this;
        $new->setHeaders([$header => $value]);

        return $new;
    }

    public function withoutHeader($header): MessageInterface
    {
        if (!is_string($header)) {
            throw new InvalidArgumentException('Header name must be an RFC 7230 compatible string');
        }

        $normalized = strtr($header, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }

        $header = $this->headerNames[$normalized];
        $new = clone $this;
        unset($new->headers[$header], $new->headerNames[$normalized]);

        return $new;
    }

    public function getHeader($header): array
    {
        if (!is_string($header)) {
            throw new InvalidArgumentException('Header name must be an RFC 7230 compatible string');
        }

        $header = strtr($header, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
        if (!isset($this->headerNames[$header])) {
            return [];
        }

        $header = $this->headerNames[$header];

        return $this->headers[$header];
    }

    public function deleteHeader(string $name): static
    {
        unset($this->headers[$name]);

        return $this;
    }

    public function setContentType(string $type, string $charset = null): static
    {
        $this->setHeader('Content-Type', $type . ($charset ? '; charset=' . $charset : ''));
        return $this;
    }

    public function redirect(string $url, int $code = 302): void
    {
        $this->setCode($code);
        $this->setHeader('Location', $url);
    }

    public function isSent(): bool
    {
        return $this->sent;
    }

    public function setSent(bool $sent): static
    {
        $this->sent = true;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatus($code, $reasonPhrase = '')
    {
        $this->statusCode = $code;

        if ((null === $reasonPhrase || '' === $reasonPhrase) && isset(self::PHRASES[$this->statusCode])) {
            $reasonPhrase = self::PHRASES[$this->statusCode];
        }
        $this->reasonPhrase = $reasonPhrase;
    }

    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        if (!is_int($code) && !is_string($code)) {
            throw new InvalidArgumentException('Status code has to be an integer');
        }

        $code = (int) $code;
        if ($code < 100 || $code > 599) {
            throw new InvalidArgumentException(\sprintf('Status code has to be an integer between 100 and 599. A status code of %d was given', $code));
        }

        $new = clone $this;
        $new->statusCode = $code;
        if ((null === $reasonPhrase || '' === $reasonPhrase) && isset(self::PHRASES[$new->statusCode])) {
            $reasonPhrase = self::PHRASES[$new->statusCode];
        }
        $new->reasonPhrase = $reasonPhrase;

        return $new;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getBody(): StreamInterface
    {
        if (null === $this->stream) {
            $this->stream = Stream::create('');
        }

        return $this->stream;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        if ($body === $this->stream) {
            return $this;
        }

        $new = clone $this;
        $new->stream = $body;

        return $new;
    }

    public function setBody(string $body)
    {
        $this->stream = Stream::create($body);
    }

    public function setCookie(string $name, string $value, $expire, ?string $path = null, ?string $domain = null, ?bool $secure = null, ?bool $httpOnly = null, ?string $sameSite = null): static
    {
        $headerValue = sprintf('%s=%s; path=%s; SameSite=%s', $name, urlencode($value), $path ?? ($domain ? '/' : $this->cookiePath), $sameSite ?? 'Lax');

        if($expire)
        {
            $headerValue .= '; Expires='.(date('D, d M Y H:i:s T', strtotime($expire)));
        }

        $cookieDomain = $domain ?? $this->cookieDomain;
        if($cookieDomain && !$path) $headerValue .= '; domain='.$cookieDomain;

        if($secure ?? $this->cookieSecure) $headerValue .= '; secure';

        if($httpOnly || $httpOnly === null) $headerValue .= '; HttpOnly';

        $this->addHeader('Set-Cookie', $headerValue);

        return $this;
    }

    public function deleteCookie(string $name, string $path = null, string $domain = null, bool $secure = null): static
    {
        $this->setCookie($name, '', 0, $path, $domain, $secure);

        return $this;
    }
}
