<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

interface OptionInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param InputInterface $input
     * @param StyleInterface $io
     * @return string
     */
    public function get(InputInterface $input, StyleInterface $io): string;
}
