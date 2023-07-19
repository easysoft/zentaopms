<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\KeyValue;

use Psr\SimpleCache\CacheInterface;
use Spiral\RoadRunner\KeyValue\Exception\InvalidArgumentException;

interface TtlAwareCacheInterface extends CacheInterface
{
    /**
     * @param string $key
     * @return \DateTimeInterface|null
     *
     * @throws InvalidArgumentException
     */
    public function getTtl(string $key): ?\DateTimeInterface;

    /**
     * @param iterable<string> $keys
     * @return iterable<string, \DateTimeInterface|null>
     *
     * @throws InvalidArgumentException
     */
    public function getMultipleTtl(iterable $keys = []): iterable;
}
