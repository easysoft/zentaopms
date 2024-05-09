<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\ArrayObj;
use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ArrayObjTest extends TestCase
{
    public function testBuildRaw(): void
    {
        $component = new ArrayObj(['a', 'b'], []);
        $this->assertEquals('(a, b)', ArrayObj::build($component));
    }

    public function testBuildValues(): void
    {
        $component = new ArrayObj([], ['a', 'b']);
        $this->assertEquals('(a, b)', ArrayObj::build($component));
    }

    public function testParseType(): void
    {
        $components = ArrayObj::parse(
            new Parser(),
            $this->getTokensList('(1 + 2, 3 + 4)'),
            [
                'type' => Expression::class,
                'typeOptions' => ['breakOnParentheses' => true],
            ]
        );
        $this->assertInstanceOf(Expression::class, $components[0]);
        $this->assertInstanceOf(Expression::class, $components[1]);
        $this->assertEquals($components[0]->expr, '1 + 2');
        $this->assertEquals($components[1]->expr, '3 + 4');
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
            ['parser/parseArrayErr1'],
            ['parser/parseArrayErr3'],
        ];
    }
}
