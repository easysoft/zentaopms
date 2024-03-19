<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\ExpressionArray;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ExpressionArrayTest extends TestCase
{
    public function testParse(): void
    {
        $component = ExpressionArray::parse(
            new Parser(),
            $this->getTokensList('(expr)'),
            ['breakOnParentheses' => true]
        );
        $this->assertEquals([], $component);
    }

    public function testParse2(): void
    {
        $component = ExpressionArray::parse(
            new Parser(),
            $this->getTokensList('(expr) +'),
            ['parenthesesDelimited' => true]
        );
        $this->assertCount(1, $component);
        $this->assertEquals('(expr)', $component[0]->expr);
    }

    public function testParseWithCommentsNoOptions(): void
    {
        $component = ExpressionArray::parse(
            new Parser(),
            $this->getTokensList('(expr) -- comment ?')
        );
        $this->assertCount(1, $component);
        $this->assertEquals('(expr)', $component[0]->expr);
    }

    public function testParseWithCommentsAndOptions(): void
    {
        $component = ExpressionArray::parse(
            new Parser(),
            $this->getTokensList('(expr -- comment ?)'),
            ['parenthesesDelimited' => true]
        );
        $this->assertCount(1, $component);
        $this->assertEquals('(expr', $component[0]->expr);
    }
}
