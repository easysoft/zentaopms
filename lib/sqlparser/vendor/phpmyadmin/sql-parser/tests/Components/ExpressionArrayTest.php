<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\ExpressionArray;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ExpressionArrayTest extends TestCase
{
    public function testParse()
    {
        $component = ExpressionArray::parse(
            new Parser(),
            $this->getTokensList('(expr)'),
            array(
                'breakOnParentheses' => true
            )
        );
        $this->assertEquals(array(), $component);
    }

    public function testParse2()
    {
        $component = ExpressionArray::parse(
            new Parser(),
            $this->getTokensList('(expr) +'),
            array(
                'parenthesesDelimited' => true
            )
        );
        $this->assertCount(1, $component);
        $this->assertEquals('(expr)', $component[0]->expr);
    }

    public function testParseWithCommentsNoOptions()
    {
        $component = ExpressionArray::parse(
            new Parser(),
            $this->getTokensList('(expr) -- comment ?')
        );
        $this->assertCount(1, $component);
        $this->assertEquals('(expr)', $component[0]->expr);
    }

    public function testParseWithCommentsAndOptions()
    {
        $component = ExpressionArray::parse(
            new Parser(),
            $this->getTokensList('(expr -- comment ?)'),
            array(
                'parenthesesDelimited' => true
            )
        );
        $this->assertCount(1, $component);
        $this->assertEquals('(expr', $component[0]->expr);
    }
}
