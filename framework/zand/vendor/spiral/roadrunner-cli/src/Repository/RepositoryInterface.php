<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Repository;

interface RepositoryInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return ReleasesCollection|iterable<ReleaseInterface>
     */
    public function getReleases(): ReleasesCollection;
}
