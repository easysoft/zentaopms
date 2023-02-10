<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\OptionsArray;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class OptionsArrayTest extends TestCase
{
    public function testParse()
    {
        $component = OptionsArray::parse(
            new Parser(),
            $this->getTokensList('A B = /*comment*/ (test) C'),
            array(
                'A' => 1,
                'B' => array(
                    2,
                    'var',
                ),
                'C' => 3
            )
        );
        $this->assertEquals(
            array(
                1 => 'A',
                2 => array(
                    'name' => 'B',
                    'expr' => '(test)',
                    'value' => 'test',
                    'equals' => true,
                ),
                3 => 'C',
            ),
            $component->options
        );
    }

    public function testParseExpr()
    {
        $component = OptionsArray::parse(
            new Parser(),
            $this->getTokensList('SUM = (3 + 5) RESULT = 8'),
            array(
                'SUM' => array(
                    1,
                    'expr',
                    array('parenthesesDelimited' => true),
                ),
                'RESULT' => array(
                    2,
                    'var',
                )
            )
        );
        $this->assertEquals('(3 + 5)', (string) $component->has('SUM', true));
        $this->assertEquals('8', $component->has('RESULT'));
    }

    public function testHas()
    {
        $component = OptionsArray::parse(
            new Parser(),
            $this->getTokensList('A B = /*comment*/ (test) C'),
            array(
                'A' => 1,
                'B' => array(
                    2,
                    'var',
                ),
                'C' => 3
            )
        );
        $this->assertTrue($component->has('A'));
        $this->assertEquals('test', $component->has('B'));
        $this->assertTrue($component->has('C'));
        $this->assertFalse($component->has('D'));
    }

    public function testRemove()
    {
        /* Assertion 1 */
        $component = new OptionsArray(array('a', 'b', 'c'));
        $this->assertTrue($component->remove('b'));
        $this->assertFalse($component->remove('d'));
        $this->assertEquals($component->options, array(0 => 'a', 2 => 'c'));

        /* Assertion 2 */
        $component = OptionsArray::parse(
            new Parser(),
            $this->getTokensList('A B = /*comment*/ (test) C'),
            array(
                'A' => 1,
                'B' => array(
                    2,
                    'var',
                ),
                'C' => 3
            )
        );
        $this->assertEquals('test', $component->has('B'));
        $component->remove('B');
        $this->assertFalse($component->has('B'));
    }

    public function testMerge()
    {
        $component = new OptionsArray(array('a'));
        $component->merge(array('b', 'c'));
        $this->assertEquals($component->options, array('a', 'b', 'c'));
    }

    public function testBuild()
    {
        $component = new OptionsArray(
            array(
                'ALL',
                'SQL_CALC_FOUND_ROWS',
                array(
                    'name' => 'MAX_STATEMENT_TIME',
                    'value' => '42',
                    'equals' => true,
                ),
            )
        );
        $this->assertEquals(
            OptionsArray::build($component),
            'ALL SQL_CALC_FOUND_ROWS MAX_STATEMENT_TIME=42'
        );
    }
}
