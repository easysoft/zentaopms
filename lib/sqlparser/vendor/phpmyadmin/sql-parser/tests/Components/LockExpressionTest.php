<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\LockExpression;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class LockExpressionTest extends TestCase
{
    public function testParse()
    {
        $component = LockExpression::parse(new Parser(), $this->getTokensList('table1 AS t1 READ LOCAL'));
        $this->assertNotNull($component->table);
        $this->assertEquals($component->table->table, 'table1');
        $this->assertEquals($component->table->alias, 't1');
        $this->assertEquals($component->type, 'READ LOCAL');
    }

    public function testParse2()
    {
        $component = LockExpression::parse(new Parser(), $this->getTokensList('table1 LOW_PRIORITY WRITE'));
        $this->assertNotNull($component->table);
        $this->assertEquals($component->table->table, 'table1');
        $this->assertEquals($component->type, 'LOW_PRIORITY WRITE');
    }

    /**
     * @dataProvider parseErrProvider
     *
     * @param mixed $expr
     * @param mixed $error
     */
    public function testParseErr($expr, $error)
    {
        $parser = new Parser();
        LockExpression::parse($parser, $this->getTokensList($expr));
        $errors = $this->getErrorsAsArray($parser);
        $this->assertEquals($errors[0][0], $error);
    }

    public function parseErrProvider()
    {
        return array(
            array(
                'table1 AS t1',
                'Unexpected end of LOCK expression.',
            ),
            array(
                'table1 AS t1 READ WRITE',
                'Unexpected keyword.',
            ),
            array(
                'table1 AS t1 READ 2',
                'Unexpected token.',
            )
        );
    }

    public function testBuild()
    {
        $component = array(
            LockExpression::parse(new Parser(), $this->getTokensList('table1 AS t1 READ LOCAL')),
            LockExpression::parse(new Parser(), $this->getTokensList('table2 LOW_PRIORITY WRITE'))
        );
        $this->assertEquals(
            LockExpression::build($component),
            'table1 AS `t1` READ LOCAL, table2 LOW_PRIORITY WRITE'
        );
    }
}
