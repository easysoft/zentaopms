<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Command;

use Spiral\RoadRunner\Console\Environment\Stability;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @psalm-import-type StabilityType from Stability
 */
class StabilityOption extends Option
{
    /**
     * @param Command $command
     * @param string $name
     * @param string $short
     */
    public function __construct(Command $command, string $name = 'stability', string $short = 's')
    {
        parent::__construct($command, $name, $short);
    }

    /**
     * @return string
     */
    protected function getDescription(): string
    {
        return 'Release minimum stability flag';
    }

    /**
     * {@inheritDoc}
     */
    protected function default(): string
    {
        return Stability::STABILITY_STABLE;
    }

    /**
     * {@inheritDoc}
     * @return StabilityType|string
     */
    public function get(InputInterface $input, StyleInterface $io): string
    {
        $stability = parent::get($input, $io);

        if (! Stability::isValid($stability)) {
            $message = 'Possibly invalid stability (--%s=%s) option (available: %s)';
            $io->warning(\sprintf($message, $this->name, $stability, $this->choices()));
        }

        return $stability;
    }

    /**
     * @return string
     */
    private function choices(): string
    {
        return \implode(', ', Stability::all());
    }
}
