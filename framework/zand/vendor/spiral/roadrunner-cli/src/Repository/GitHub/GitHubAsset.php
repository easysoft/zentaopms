<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Repository\GitHub;

use Spiral\RoadRunner\Console\Repository\Asset;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @psalm-type GitHubAssetApiResponse = array {
 *      name: string,
 *      browser_download_url: string
 * }
 */
final class GitHubAsset extends Asset
{
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     * @param string $name
     * @param string $uri
     */
    public function __construct(HttpClientInterface $client, string $name, string $uri)
    {
        $this->client = $client;

        parent::__construct($name, $uri);
    }

    /**
     * @param HttpClientInterface $client
     * @param GitHubAssetApiResponse $asset
     * @return static
     *
     * @psalm-suppress DocblockTypeContradiction
     */
    public static function fromApiResponse(HttpClientInterface $client, array $asset): self
    {
        // Validate name
        if (! isset($asset['name']) || ! \is_string($asset['name'])) {
            throw new \InvalidArgumentException(
                'Passed array must contain "name" value of type string'
            );
        }

        // Validate uri
        if (! isset($asset['browser_download_url']) || ! \is_string($asset['browser_download_url'])) {
            throw new \InvalidArgumentException(
                'Passed array must contain "browser_download_url" key of type string'
            );
        }

        return new self($client, $asset['name'], $asset['browser_download_url']);
    }

    /**
     * {@inheritDoc}
     * @throws ExceptionInterface
     */
    public function download(\Closure $progress = null): \Traversable
    {
        $response = $this->client->request('GET', $this->getUri(), [
            'on_progress' => $progress,
        ]);

        foreach ($this->client->stream($response) as $chunk) {
            yield $chunk->getContent();
        }
    }
}
