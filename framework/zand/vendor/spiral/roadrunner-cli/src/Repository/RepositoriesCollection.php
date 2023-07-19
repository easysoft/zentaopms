<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Repository;

class RepositoriesCollection implements RepositoryInterface
{
    /**
     * @var array<RepositoryInterface>
     */
    private array $repositories;

    /**
     * @param array<RepositoryInterface> $repositories
     */
    public function __construct(array $repositories)
    {
        $this->repositories = $repositories;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'unknown/unknown';
    }

    /**
     * @return ReleasesCollection
     */
    public function getReleases(): ReleasesCollection
    {
        return ReleasesCollection::from(function () {
            foreach ($this->repositories as $repository) {
                yield from $repository->getReleases();
            }
        });
    }
}
