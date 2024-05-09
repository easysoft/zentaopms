<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\LockExpression;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class LockExpressionTest extends TestCase
{
    public function testParse(): void
    {
        $component = LockExpression::parse(new Parser(), $this->getTokensList('table1 AS t1 READ LOCAL'));
        $this->assertNotNull($component->table);
        $this->assertEquals($component->table->table, 'table1');
        $this->assertEquals($component->table->alias, 't1');
        $this->assertEquals($component->type, 'READ LOCAL');
    }

    public function testParse2(): void
    {
        $component = LockExpression::parse(new Parser(), $this->getTokensList('table1 LOW_PRIORITY WRITE'));
        $this->assertNotNull($component->table);
        $this->assertEquals($component->table->table, 'table1');
        $this->assertEquals($component->type, 'LOW_PRIORITY WRITE');
    }

    /**
     * @dataProvider parseErrProvider
     */
    public function testParseErr(string $expr, string $error): void
    {
        $parser = new Parser();
        LockExpression::parse($parser, $this->getTokensList($expr));
        $errors = $this->getErrorsAsArray($parser);
        $this->assertEquals($errors[0][0], $error);
    }

    /**
     * @return string[][]
     */
    public function parseErrProvider(): array
    {
        return [
            [
                'table1 AS t1',
                'Unexpected end of LOCK expression.',
            ],
            [
                'table1 AS t1 READ WRITE',
                'Unexpected keyword.',
            ],
            [
                'table1 AS t1 READ 2',
                'Unexpected token.',
            ],
        ];
    }

    public function testBuild(): void
    {
        $component = [
            LockExpression::parse(new Parser(), $this->getTokensList('table1 AS t1 READ LOCAL')),
            LockExpression::parse(new Parser(), $this->getTokensList('table2 LOW_PRIORITY WRITE')),
        ];
        $this->assertEquals(
            LockExpression::build($component),
            'table1 AS `t1` READ LOCAL, table2 LOW_PRIORITY WRITE'
        );
    }
}
