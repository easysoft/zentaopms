<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Archive;

abstract class Archive implements ArchiveInterface
{
    /**
     * @param \SplFileInfo $archive
     */
    public function __construct(\SplFileInfo $archive)
    {
        $this->assertArchiveValid($archive);
    }

    /**
     * @param \SplFileInfo $archive
     */
    private function assertArchiveValid(\SplFileInfo $archive): void
    {
        if (! $archive->isFile()) {
            throw new \InvalidArgumentException(
                \sprintf('Archive "%s" is not a file', $archive->getFilename())
            );
        }

        if (! $archive->isReadable()) {
            throw new \InvalidArgumentException(
                \sprintf('Archive file "%s" is not readable', $archive->getFilename())
            );
        }
    }
}
