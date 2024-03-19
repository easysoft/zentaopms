<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Utils;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Utils\Routine;

class RoutineTest extends TestCase
{
    /**
     * @param string[] $expected
     *
     * @dataProvider getReturnTypeProvider
     */
    public function testGetReturnType(string $def, array $expected): void
    {
        $this->assertEquals($expected, Routine::getReturnType($def));
    }

    /**
     * @return array<int, array<int, string|array<int, string>>>
     * @psalm-return list<array{string, string[]}>
     */
    public function getReturnTypeProvider(): array
    {
        return [
            [
                '',
                [
                    '',
                    '',
                    '',
                    '',
                    '',
                ],
            ],
            [
                'TEXT',
                [
                    '',
                    '',
                    'TEXT',
                    '',
                    '',
                ],
            ],
            [
                'INT(20)',
                [
                    '',
                    '',
                    'INT',
                    '20',
                    '',
                ],
            ],
            [
                'INT UNSIGNED',
                [
                    '',
                    '',
                    'INT',
                    '',
                    'UNSIGNED',
                ],
            ],
            [
                'VARCHAR(1) CHARSET utf8',
                [
                    '',
                    '',
                    'VARCHAR',
                    '1',
                    'utf8',
                ],
            ],
            [
                'ENUM(\'a\', \'b\') CHARSET latin1',
                [
                    '',
                    '',
                    'ENUM',
                    '\'a\',\'b\'',
                    'latin1',
                ],
            ],
            [
                'DECIMAL(5,2) UNSIGNED ZEROFILL',
                [
                    '',
                    '',
                    'DECIMAL',
                    '5,2',
                    'UNSIGNED ZEROFILL',
                ],
            ],
            [
                'SET(\'test\'\'esc"\',   \'more\\\'esc\')',
                [
                    '',
                    '',
                    'SET',
                    '\'test\'\'esc"\',\'more\\\'esc\'',
                    '',
                ],
            ],
        ];
    }

    /**
     * @param string[] $expected
     *
     * @dataProvider getParameterProvider
     */
    public function testGetParameter(string $def, array $expected): void
    {
        $this->assertEquals($expected, Routine::getParameter($def));
    }

    /**
     * @return array<int, array<int, string|array<int, string>>>
     * @psalm-return list<array{string, string[]}>
     */
    public function getParameterProvider(): array
    {
        return [
            [
                '',
                [
                    '',
                    '',
                    '',
                    '',
                    '',
                ],
            ],
            [
                '`foo` TEXT',
                [
                    '',
                    'foo',
                    'TEXT',
                    '',
                    '',
                ],
            ],
            [
                '`foo` INT(20)',
                [
                    '',
                    'foo',
                    'INT',
                    '20',
                    '',
                ],
            ],
            [
                'IN `fo``fo` INT UNSIGNED',
                [
                    'IN',
                    'fo`fo',
                    'INT',
                    '',
                    'UNSIGNED',
                ],
            ],
            [
                'OUT bar VARCHAR(1) CHARSET utf8',
                [
                    'OUT',
                    'bar',
                    'VARCHAR',
                    '1',
                    'utf8',
                ],
            ],
            [
                '`"baz\'\'` ENUM(\'a\', \'b\') CHARSET latin1',
                [
                    '',
                    '"baz\'\'',
                    'ENUM',
                    '\'a\',\'b\'',
                    'latin1',
                ],
            ],
            [
                'INOUT `foo` DECIMAL(5,2) UNSIGNED ZEROFILL',
                [
                    'INOUT',
                    'foo',
                    'DECIMAL',
                    '5,2',
                    'UNSIGNED ZEROFILL',
                ],
            ],
            [
                '`foo``s func` SET(\'test\'\'esc"\',   \'more\\\'esc\')',
                [
                    '',
                    'foo`s func',
                    'SET',
                    '\'test\'\'esc"\',\'more\\\'esc\'',
                    '',
                ],
            ],
        ];
    }

    /**
     * @param array<string, int|string[]|string[][]> $expected
     * @psalm-param array{
     *   num: int,
     *   dir: string[],
     *   name: string[],
     *   type: string[],
     *   length: string[],
     *   length_arr: string[][],
     *   opts: string[]
     * } $expected
     *
     * @dataProvider getParametersProvider
     */
    public function testGetParameters(string $query, array $expected): void
    {
        $parser = new Parser($query);
        $this->assertEquals($expected, Routine::getParameters($parser->statements[0]));
    }

    /**
     * @return array<int, array<int, string|array<string, int|string[]|string[][]>>>
     * @psalm-return list<array{string, array{
     *   num: int,
     *   dir: string[],
     *   name: string[],
     *   type: string[],
     *   length: string[],
     *   length_arr: string[][],
     *   opts: string[]
     * }}>
     */
    public function getParametersProvider(): array
    {
        return [
            [
                'CREATE PROCEDURE `foo`() SET @A=0',
                [
                    'num' => 0,
                    'dir' => [],
                    'name' => [],
                    'type' => [],
                    'length' => [],
                    'length_arr' => [],
                    'opts' => [],
                ],
            ],
            [
                'CREATE DEFINER=`user\\`@`somehost``(` FUNCTION `foo```(`baz` INT) BEGIN SELECT NULL; END',
                [
                    'num' => 1,
                    'dir' => [0 => ''],
                    'name' => [0 => 'baz'],
                    'type' => [0 => 'INT'],
                    'length' => [0 => ''],
                    'length_arr' => [
                        0 => [],
                    ],
                    'opts' => [0 => ''],
                ],
            ],
            [
                'CREATE PROCEDURE `foo`(IN `baz\\)` INT(25) zerofill unsigned) BEGIN SELECT NULL; END',
                [
                    'num' => 1,
                    'dir' => [0 => 'IN'],
                    'name' => [0 => 'baz\\)'],
                    'type' => [0 => 'INT'],
                    'length' => [0 => '25'],
                    'length_arr' => [
                        0 => ['25'],
                    ],
                    'opts' => [0 => 'UNSIGNED ZEROFILL'],
                ],
            ],
            [
                'CREATE PROCEDURE `foo`(IN `baz\\` INT(001) zerofill, out bazz varchar(15) charset utf8) ' .
                'BEGIN SELECT NULL; END',
                [
                    'num' => 2,
                    'dir' => [
                        0 => 'IN',
                        1 => 'OUT',
                    ],
                    'name' => [
                        0 => 'baz\\',
                        1 => 'bazz',
                    ],
                    'type' => [
                        0 => 'INT',
                        1 => 'VARCHAR',
                    ],
                    'length' => [
                        0 => '1',
                        1 => '15',
                    ],
                    'length_arr' => [
                        0 => ['1'],
                        1 => ['15'],
                    ],
                    'opts' => [
                        0 => 'ZEROFILL',
                        1 => 'utf8',
                    ],
                ],
            ],
        ];
    }
}
