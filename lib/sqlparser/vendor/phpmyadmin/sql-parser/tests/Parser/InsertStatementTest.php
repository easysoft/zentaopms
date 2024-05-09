<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class InsertStatementTest extends TestCase
{
    /**
     * @dataProvider insertProvider
     */
    public function testInsert(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function insertProvider(): array
    {
        return [
            ['parser/parseInsert'],
            ['parser/parseInsertFunction'],
            ['parser/parseInsertSelect'],
            ['parser/parseInsertOnDuplicateKey'],
            ['parser/parseInsertSetOnDuplicateKey'],
            ['parser/parseInsertSelectOnDuplicateKey'],
            ['parser/parseInsertOnDuplicateKeyErr'],
            ['parser/parseInsertErr'],
            ['parser/parseInsertErr2'],
            ['parser/parseInsertIntoErr'],
        ];
    }
}
