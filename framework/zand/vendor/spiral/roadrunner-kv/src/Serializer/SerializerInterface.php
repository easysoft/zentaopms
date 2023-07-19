<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\KeyValue\Serializer;

use Spiral\RoadRunner\KeyValue\Exception\SerializationException;

interface SerializerInterface
{
    /**
     * @throws SerializationException
     */
    public function serialize(mixed $value): string;

    /**
     * @throws SerializationException
     */
    public function unserialize(string $value): mixed;
}
