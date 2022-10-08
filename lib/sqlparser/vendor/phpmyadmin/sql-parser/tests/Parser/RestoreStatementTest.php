<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class RestoreStatementTest extends TestCase
{
    /**
     * @param mixed $test
     *
     * @dataProvider restoreProvider
     */
    public function testRestore($test): void
    {
        $this->runParserTest($test);
    }

    public function restoreProvider(): array
    {
        return [
            ['parser/parseRestore'],
        ];
    }
}
