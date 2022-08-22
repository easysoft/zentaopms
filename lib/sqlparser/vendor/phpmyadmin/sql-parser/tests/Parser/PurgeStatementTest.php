<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class PurgeStatementTest extends TestCase
{
    /**
     * @param mixed $test
     *
     * @dataProvider purgeProvider
     */
    public function testPurge($test): void
    {
        $this->runParserTest($test);
    }

    public function purgeProvider(): array
    {
        return [
            ['parser/parsePurge'],
            ['parser/parsePurge2'],
            ['parser/parsePurge3'],
            ['parser/parsePurge4'],
            ['parser/parsePurgeErr'],
            ['parser/parsePurgeErr2'],
            ['parser/parsePurgeErr3'],
        ];
    }
}
