<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class RenameStatementTest extends TestCase
{
    /**
     * @param mixed $test
     *
     * @dataProvider renameProvider
     */
    public function testRename($test): void
    {
        $this->runParserTest($test);
    }

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
