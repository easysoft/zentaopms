<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\KeyValue;

use Spiral\Goridge\RPC\RPCInterface;
use Spiral\RoadRunner\KeyValue\Serializer\SerializerAwareTrait;
use Spiral\RoadRunner\KeyValue\Serializer\SerializerInterface;
use Spiral\RoadRunner\KeyValue\Serializer\DefaultSerializer;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class Factory implements FactoryInterface
{
    use SerializerAwareTrait;

    private RPCInterface $rpc;

    public function __construct(RPCInterface $rpc, SerializerInterface $serializer = null)
    {
        $this->rpc = $rpc;
        $this->setSerializer($serializer ?? new DefaultSerializer());
    }

    public function select(string $name): StorageInterface
    {
        return new Cache($this->rpc, $name, $this->getSerializer());
    }
}
