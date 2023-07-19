<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Archive;

final class ZipPharArchive extends PharAwareArchive
{
    /**
     * @param \SplFileInfo $file
     * @return \PharData
     */
    protected function open(\SplFileInfo $file): \PharData
    {
        $format = \Phar::ZIP | \Phar::GZ;

        return new \PharData($file->getPathname(), 0, null, $format);
    }
}
