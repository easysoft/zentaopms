<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Misc;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class ParameterTest extends TestCase
{
    /**
     * @dataProvider parameterProvider
     */
    public function testParameter(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function parameterProvider(): array
    {
        return [
            ['misc/parseParameter'],
            ['misc/parseParameter2'],
        ];
    }
}
