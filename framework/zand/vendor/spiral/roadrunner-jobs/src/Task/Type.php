<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs\Task;

/**
 * @psalm-type TypeEnum = Type::*
 */
interface Type
{
    /**
     * @var TypeEnum
     */
    public const SUCCESS = 0;

    /**
     * @var TypeEnum
     */
    public const ERROR = 1;
}
