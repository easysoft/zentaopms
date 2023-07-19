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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @psalm-type InputOptionType = InputOption::VALUE_*
 */
abstract class Option implements OptionInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @param Command $command
     * @param string $name
     * @param string|null $short
     */
    public function __construct(Command $command, string $name, string $short = null)
    {
        $this->name = $name;

        $this->register($command, $name, $short ?? $name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Command $command
     * @param string $name
     * @param string $short
     */
    private function register(Command $command, string $name, string $short): void
    {
        $command->addOption($name, $short, $this->getMode(), $this->getDescription(), $this->default());
    }

    /**
     * @return InputOptionType
     */
    protected function getMode(): int
    {
        return InputOption::VALUE_OPTIONAL;
    }

    /**
     * @return string
     */
    abstract protected function getDescription(): string;

    /**
     * @param InputInterface $input
     * @param StyleInterface $io
     * @return string
     */
    public function get(InputInterface $input, StyleInterface $io): string
    {
        $result = $input->getOption($this->name) ?: $this->default();

        return \is_string($result) ? $result : '';
    }

    /**
     * @return string|null
     */
    abstract protected function default(): ?string;
}
