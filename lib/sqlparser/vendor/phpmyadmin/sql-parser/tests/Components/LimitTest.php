<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\Limit;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class LimitTest extends TestCase
{
    public function testBuildWithoutOffset(): void
    {
        $component = new Limit(1);
        $this->assertEquals(Limit::build($component), '0, 1');
    }

    public function testBuildWithOffset(): void
    {
        $component = new Limit(1, 2);
        $this->assertEquals(Limit::build($component), '2, 1');
    }

    /**
     * @dataProvider parseProvider
     */
    public function testParse(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function parseProvider(): array
    {
        return [
            ['parser/parseLimitErr1'],
            ['parser/parseLimitErr2'],
        ];
    }
}
