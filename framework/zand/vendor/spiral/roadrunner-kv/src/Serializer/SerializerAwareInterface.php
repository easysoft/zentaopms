<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\KeyValue\Serializer;

interface SerializerAwareInterface
{
    /**
     * @return $this
     */
    public function withSerializer(SerializerInterface $serializer): self;

    public function getSerializer(): SerializerInterface;
}
