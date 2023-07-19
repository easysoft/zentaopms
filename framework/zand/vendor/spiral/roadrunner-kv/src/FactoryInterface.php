<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\KeyValue;

use Spiral\RoadRunner\KeyValue\Serializer\SerializerAwareInterface;

interface FactoryInterface extends SerializerAwareInterface
{
    /**
     * Create a shared cache storage by its name.
     *
     * @param string $name
     * @return StorageInterface
     */
    public function select(string $name): StorageInterface;
}
