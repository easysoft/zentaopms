<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class AlterStatementTest extends TestCase
{
    /**
     * @param mixed $test
     *
     * @dataProvider alterProvider
     */
    public function testAlter($test): void
    {
        $this->runParserTest($test);
    }

    public function alterProvider(): array
    {
        return [
            ['parser/parseAlter'],
            ['parser/parseAlter2'],
            ['parser/parseAlter3'],
            ['parser/parseAlter4'],
            ['parser/parseAlter5'],
            ['parser/parseAlter6'],
            ['parser/parseAlter7'],
            ['parser/parseAlter8'],
            ['parser/parseAlter9'],
            ['parser/parseAlter10'],
            ['parser/parseAlter11'],
            ['parser/parseAlter12'],
            ['parser/parseAlter13'],
            ['parser/parseAlterErr'],
            ['parser/parseAlterErr2'],
            ['parser/parseAlterErr3'],
            ['parser/parseAlterErr4'],
            ['parser/parseAlterWithInvisible'],
            ['parser/parseAlterTableCharacterSet1'],
            ['parser/parseAlterTableCharacterSet2'],
            ['parser/parseAlterTableCharacterSet3'],
            ['parser/parseAlterTableCharacterSet4'],
            ['parser/parseAlterTableCharacterSet5'],
            ['parser/parseAlterTableCharacterSet6'],
            ['parser/parseAlterTableCharacterSet7'],
            ['parser/parseAlterUser'],
            ['parser/parseAlterUser1'],
            ['parser/parseAlterUser2'],
            ['parser/parseAlterUser3'],
            ['parser/parseAlterUser4'],
            ['parser/parseAlterUser5'],
            ['parser/parseAlterUser6'],
            ['parser/parseAlterUser7'],
            ['parser/parseAlterUser8'],
        ];
    }
}
