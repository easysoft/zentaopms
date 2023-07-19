<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\KeyValue\Serializer;

trait SerializerAwareTrait
{
    protected SerializerInterface $serializer;

    protected function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    /**
     * @param SerializerInterface $serializer
     * @return $this
     */
    public function withSerializer(SerializerInterface $serializer): self
    {
        $self = clone $this;
        $self->setSerializer($serializer);

        return $self;
    }

    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }
}
