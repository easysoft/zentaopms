<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class RenameStatementTest extends TestCase
{
    /**
     * @dataProvider renameProvider
     */
    public function testRename(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function renameProvider(): array
    {
        return [
            ['parser/parseRename'],
            ['parser/parseRename2'],
            ['parser/parseRenameErr1'],
            ['parser/parseRenameErr2'],
            ['parser/parseRenameErr3'],
            ['parser/parseRenameErr4'],
            ['parser/parseRenameErr5'],
        ];
    }
}
