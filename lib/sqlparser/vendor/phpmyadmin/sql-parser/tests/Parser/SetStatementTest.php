<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class SetStatementTest extends TestCase
{
    /**
     * @param mixed $test
     *
     * @dataProvider setProvider
     */
    public function testSet($test): void
    {
        $this->runParserTest($test);
    }

    public function setProvider(): array
    {
        return [
            ['parser/parseSetCharset'],
            ['parser/parseSetCharsetError'],
            ['parser/parseSetCharacterSet'],
            ['parser/parseSetCharacterSetError'],
            ['parser/parseAlterTableSetAutoIncrementError'],
            ['parser/parseSetNames'],
            ['parser/parseSetNamesError'],
            ['parser/parseSetError1'],
            ['parser/parseInsertIntoSet'],
        ];
    }
}
