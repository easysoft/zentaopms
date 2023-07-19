<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner;

use JetBrains\PhpStorm\ExpectedValues;
use Spiral\RoadRunner\Environment\Mode;

/**
 * Provides base values to configure roadrunner worker.
 *
 * @psalm-import-type ModeType from Mode
 * @see Mode
 */
interface EnvironmentInterface
{
    /**
     * Returns worker mode assigned to the PHP process.
     *
     * @return ModeType|string
     */
    #[ExpectedValues(valuesFromClass: Mode::class)]
    public function getMode(): string;

    /**
     * Address worker should be connected to (or pipes).
     *
     * @return string
     */
    public function getRelayAddress(): string;

    /**
     * RPC address.
     *
     * @return string
     */
    public function getRPCAddress(): string;
}
