<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class DropStatementTest extends TestCase
{
    /**
     * @param mixed $test
     *
     * @dataProvider dropProvider
     */
    public function testDrop($test): void
    {
        $this->runParserTest($test);
    }

    public function dropProvider(): array
    {
        return [
            ['parser/parseDrop'],
            ['parser/parseDrop2'],
        ];
    }
}
