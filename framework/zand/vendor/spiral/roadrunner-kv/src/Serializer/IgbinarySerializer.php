<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\KeyValue\Serializer;

class IgbinarySerializer implements SerializerInterface
{
    private const SUPPORTED_VERSION_MIN = '3.1.6';

    private const ERROR_NOT_AVAILABLE =
        'The "ext-igbinary" PHP extension is not available';

    private const ERROR_NON_COMPATIBLE =
        'Current version of the "ext-igbinary" PHP extension (v%s) does not meet the requirements, ' .
        'version v' . self::SUPPORTED_VERSION_MIN . ' or higher required';

    /**
     * @codeCoverageIgnore Reason: Contains only initialization assertion
     * @throws \LogicException
     */
    public function __construct()
    {
        $this->assertAvailable();
    }

    /**
     * @codeCoverageIgnore Reason: Ignore environment-aware assertions
     */
    private function assertAvailable(): void
    {
        if (! \extension_loaded('igbinary')) {
            throw new \LogicException(self::ERROR_NOT_AVAILABLE);
        }

        if (\version_compare(self::SUPPORTED_VERSION_MIN, \phpversion('igbinary'), '>')) {
            throw new \LogicException(\sprintf(self::ERROR_NON_COMPATIBLE, \phpversion('igbinary')));
        }
    }

    public function serialize(mixed $value): string
    {
        return \igbinary_serialize($value);
    }

    public function unserialize(string $value): mixed
    {
        return \igbinary_unserialize($value);
    }
}
