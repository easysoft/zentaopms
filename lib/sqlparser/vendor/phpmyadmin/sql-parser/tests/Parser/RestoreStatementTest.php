<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class RestoreStatementTest extends TestCase
{
    /**
     * @dataProvider restoreProvider
     */
    public function testRestore(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function restoreProvider(): array
    {
        return [
            ['parser/parseRestore'],
        ];
    }
}
