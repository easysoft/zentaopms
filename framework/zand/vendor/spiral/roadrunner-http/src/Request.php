<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Http;

use JetBrains\PhpStorm\Immutable;

/**
 * @psalm-immutable
 *
 * @psalm-type UploadedFile = array {
 *      name:       string,
 *      error:      positive-int|0,
 *      tmpName:    string,
 *      size:       positive-int|0,
 *      mime:       string
 * }
 *
 * @psalm-type HeadersList = array<string, array<array-key, string>>
 * @psalm-type AttributesList = array<string, mixed>
 * @psalm-type QueryArgumentsList = array<string, string>
 * @psalm-type CookiesList = array<string, string>
 * @psalm-type UploadedFilesList = array<array-key, UploadedFile>
 */
#[Immutable]
final class Request
{
    public const PARSED_BODY_ATTRIBUTE_NAME = 'rr_parsed_body';

    /**
     * @var string
     */
    public string $remoteAddr = '127.0.0.1';

    /**
     * @var string
     */
    public string $protocol = 'HTTP/1.0';

    /**
     * @var string
     */
    public string $method = 'GET';

    /**
     * @var string
     */
    public string $uri = 'http://localhost';

    /**
     * @var HeadersList
     */
    public array $headers = [];

    /**
     * @var CookiesList
     */
    public array $cookies = [];

    /**
     * @var UploadedFilesList
     */
    public array $uploads = [];

    /**
     * @var AttributesList
     */
    public array $attributes = [];

    /**
     * @var QueryArgumentsList
     */
    public array $query = [];

    /**
     * @var string
     */
    public string $body = '';

    /**
     * @var bool
     */
    public bool $parsed = false;

    /**
     * @return string
     */
    public function getRemoteAddr(): string
    {
        return (string)($this->attributes['ipAddress'] ?? $this->remoteAddr);
    }

    /**
     * @return array|null
     * @throws \JsonException
     */
    public function getParsedBody(): ?array
    {
        if ($this->parsed) {
            return (array)\json_decode($this->body, true, 512, \JSON_THROW_ON_ERROR);
        }

        return null;
    }
}
