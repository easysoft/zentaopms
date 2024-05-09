<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class ReplaceStatementTest extends TestCase
{
    /**
     * @dataProvider replaceProvider
     */
    public function testReplace(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function replaceProvider(): array
    {
        return [
            ['parser/parseReplace'],
            ['parser/parseReplace2'],
            ['parser/parseReplaceValues'],
            ['parser/parseReplaceSet'],
            ['parser/parseReplaceSelect'],
            ['parser/parseReplaceErr'],
            ['parser/parseReplaceErr2'],
            ['parser/parseReplaceErr3'],
            ['parser/parseReplaceIntoErr'],
        ];
    }
}
