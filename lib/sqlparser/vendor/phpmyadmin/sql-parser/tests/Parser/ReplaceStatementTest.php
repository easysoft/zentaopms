<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class ReplaceStatementTest extends TestCase
{
    /**
     * @param mixed $test
     *
     * @dataProvider replaceProvider
     */
    public function testReplace($test): void
    {
        $this->runParserTest($test);
    }

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
