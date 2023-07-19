<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Environment;

/**
 * @psalm-type ModeType = Mode::MODE_*
 */
interface Mode
{
    /**
     * @var string
     */
    public const MODE_HTTP = 'http';

    /**
     * @var string
     */
    public const MODE_TEMPORAL = 'temporal';
    
    /**
     * @var string
     */
    public const MODE_JOBS = 'jobs';

    /**
     * @var string
     */
    public const MODE_GRPC = 'grpc';

    /**
     * @var string
     */
    public const MODE_TCP = 'tcp';

    /**
     * @var string
     */
    public const MODE_CENTRIFUGE = 'centrifuge';
}
