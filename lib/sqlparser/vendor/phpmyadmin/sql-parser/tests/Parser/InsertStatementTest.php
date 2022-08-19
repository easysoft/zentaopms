<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class InsertStatementTest extends TestCase
{
    /**
     * @param mixed $test
     *
     * @dataProvider insertProvider
     */
    public function testInsert($test): void
    {
        $this->runParserTest($test);
    }

    public function insertProvider(): array
    {
        return [
            ['parser/parseInsert'],
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
