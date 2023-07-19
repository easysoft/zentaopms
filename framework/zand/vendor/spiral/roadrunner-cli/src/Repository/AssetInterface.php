<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Repository;

interface AssetInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @param \Closure|null $progress
     * @return iterable<mixed, string>
     */
    public function download(\Closure $progress = null): iterable;
}
