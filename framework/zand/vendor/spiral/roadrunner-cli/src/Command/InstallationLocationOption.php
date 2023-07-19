<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

class InstallationLocationOption extends Option
{
    /**
     * @param Command $command
     * @param string $name
     * @param string $short
     */
    public function __construct(Command $command, string $name = 'location', string $short = 'l')
    {
        parent::__construct($command, $name, $short);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDescription(): string
    {
        return 'Installation directory';
    }

    /**
     * {@inheritDoc}
     */
    protected function default(): string
    {
        return \getcwd() ?: '.';
    }

    /**
     * {@inheritDoc}
     */
    public function get(InputInterface $input, StyleInterface $io): string
    {
        $location = parent::get($input, $io);

        if (! \is_dir($location) || ! \is_writable($location)) {
            $message = 'Invalid installation directory (--%s=%s) option';
            $message = \sprintf($message, $this->name, $location);

            $io->warning($message);

            throw new \InvalidArgumentException('Installation directory not found or not writable');
        }

        return $location;
    }
}
