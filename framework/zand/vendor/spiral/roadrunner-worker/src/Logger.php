<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class Logger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * {@inheritDoc}
     * @psalm-suppress RedundantConditionGivenDocblockType
     */
    public function log($level, $message, array $context = []): void
    {
        assert(\is_scalar($level), 'Invalid log level type');
        assert(\is_string($message), 'Invalid log message type');

        $this->write($this->format((string)$level, $message, $context));
    }

    /**
     * @param string $message
     */
    protected function write(string $message): void
    {
        \file_put_contents('php://stderr', $message);
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function format(string $level, string $message, array $context = []): string
    {
        return \sprintf('[php %s] %s %s', $level, $message, $this->formatContext($context));
    }

    /**
     * @param array $context
     * @return string
     */
    protected function formatContext(array $context): string
    {
        try {
            return \json_encode($context, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $_) {
            return \print_r($context, true);
        }
    }
}
