<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class DropStatementTest extends TestCase
{
    /**
     * @dataProvider dropProvider
     */
    public function testDrop(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function dropProvider(): array
    {
        return [
            ['parser/parseDrop'],
            ['parser/parseDrop2'],
        ];
    }
}
