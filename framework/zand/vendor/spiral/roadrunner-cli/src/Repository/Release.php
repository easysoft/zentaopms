<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Repository;

use Composer\Semver\Semver;
use Composer\Semver\VersionParser;
use JetBrains\PhpStorm\ExpectedValues;
use Spiral\RoadRunner\Console\Environment\Stability;

abstract class Release implements ReleaseInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    #[ExpectedValues(valuesFromClass: Stability::class)]
    private string $stability;

    /**
     * @var string
     */
    private string $version;

    /**
     * @var AssetsCollection
     */
    private AssetsCollection $assets;

    /**
     * @var string
     */
    private string $repository;

    /**
     * @param string $name
     * @param string $version
     * @param string $repository
     * @param iterable $assets
     */
    public function __construct(string $name, string $version, string $repository, iterable $assets = [])
    {
        $this->version = $version;
        $this->repository = $repository;

        $this->name = $this->simplifyReleaseName($name);
        $this->assets = AssetsCollection::create($assets);

        $this->stability = $this->parseStability($version);
    }

    /**
     * @param string $version
     * @return string
     */
    private function parseStability(string $version): string
    {
        return VersionParser::parseStability($version);
    }

    /**
     * @param string $name
     * @return string
     */
    private function simplifyReleaseName(string $name): string
    {
        $version = (new VersionParser())->normalize($name);

        $parts = \explode('-', $version);
        $number = \substr($parts[0], 0, -2);

        return isset($parts[1])
            ? $number . '-' . $parts[1]
            : $number
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getRepositoryName(): string
    {
        return $this->repository;
    }

    /**
     * {@inheritDoc}
     */
    #[ExpectedValues(valuesFromClass: Stability::class)]
    public function getStability(): string
    {
        return $this->stability;
    }

    /**
     * {@inheritDoc}
     */
    public function getAssets(): AssetsCollection
    {
        return $this->assets;
    }

    /**
     * {@inheritDoc}
     */
    public function satisfies(string $constraint): bool
    {
        return Semver::satisfies($this->getName(), $constraint);
    }
}
