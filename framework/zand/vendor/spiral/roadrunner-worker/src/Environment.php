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
 * @psalm-import-type ModeType from Mode
 * @psalm-type EnvironmentVariables = array {
 *      RR_MODE?:   ModeType|string,
 *      RR_RELAY?:  string,
 *      RR_RPC?:    string,
 * }
 * @see Mode
 */
class Environment implements EnvironmentInterface
{
    /**
     * @var EnvironmentVariables
     */
    private array $env;

    /**
     * @param EnvironmentVariables $env
     */
    public function __construct(array $env = [])
    {
        $this->env = $env;
    }

    /**
     * {@inheritDoc}
     */
    #[ExpectedValues(valuesFromClass: Mode::class)]
    public function getMode(): string
    {
        return $this->get('RR_MODE', '');
    }

    /**
     * {@inheritDoc}
     */
    public function getRelayAddress(): string
    {
        return $this->get('RR_RELAY', 'pipes');
    }

    /**
     * {@inheritDoc}
     */
    public function getRPCAddress(): string
    {
        return $this->get('RR_RPC', 'tcp://127.0.0.1:6001');
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    private function get(string $name, string $default = ''): string
    {
        if (isset($this->env[$name]) || \array_key_exists($name, $this->env)) {
            /** @psalm-suppress RedundantCastGivenDocblockType */
            return (string)$this->env[$name];
        }

        return $default;
    }

    /**
     * @return self
     */
    public static function fromGlobals(): self
    {
        /** @var array<string, string> $env */
        $env = \array_merge($_ENV, $_SERVER);

        return new self($env);
    }
}
