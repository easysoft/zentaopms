<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class CallStatementTest extends TestCase
{
    /**
     * @param mixed $test
     *
     * @dataProvider callProvider
     */
    public function testCall($test): void
    {
        $this->runParserTest($test);
    }

    public function callProvider(): array
    {
        return [
            ['parser/parseCall'],
            ['parser/parseCall2'],
            ['parser/parseCall3'],
        ];
    }
}
