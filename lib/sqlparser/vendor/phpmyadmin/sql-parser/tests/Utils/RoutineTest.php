<?php

namespace PhpMyAdmin\SqlParser\Tests\Utils;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Utils\Routine;

class RoutineTest extends TestCase
{
    /**
     * @dataProvider getReturnTypeProvider
     *
     * @param mixed $def
     */
    public function testGetReturnType($def, array $expected)
    {
        $this->assertEquals($expected, Routine::getReturnType($def));
    }

    public function getReturnTypeProvider()
    {
        return array(
            array(
                '',
                array(
                    '',
                    '',
                    '',
                    '',
                    '',
                ),
            ),
            array(
                'TEXT',
                array(
                    '',
                    '',
                    'TEXT',
                    '',
                    '',
                ),
            ),
            array(
                'INT(20)',
                array(
                    '',
                    '',
                    'INT',
                    '20',
                    '',
                ),
            ),
            array(
                'INT UNSIGNED',
                array(
                    '',
                    '',
                    'INT',
                    '',
                    'UNSIGNED',
                ),
            ),
            array(
                'VARCHAR(1) CHARSET utf8',
                array(
                    '',
                    '',
                    'VARCHAR',
                    '1',
                    'utf8',
                ),
            ),
            array(
                'ENUM(\'a\', \'b\') CHARSET latin1',
                array(
                    '',
                    '',
                    'ENUM',
                    '\'a\',\'b\'',
                    'latin1',
                ),
            ),
            array(
                'DECIMAL(5,2) UNSIGNED ZEROFILL',
                array(
                    '',
                    '',
                    'DECIMAL',
                    '5,2',
                    'UNSIGNED ZEROFILL',
                ),
            ),
            array(
                'SET(\'test\'\'esc"\',   \'more\\\'esc\')',
                array(
                    '',
                    '',
                    'SET',
                    '\'test\'\'esc"\',\'more\\\'esc\'',
                    '',
                ),
            )
        );
    }

    /**
     * @dataProvider getParameterProvider
     *
     * @param mixed $def
     */
    public function testGetParameter($def, array $expected)
    {
        $this->assertEquals($expected, Routine::getParameter($def));
    }

    public function getParameterProvider()
    {
        return array(
            array(
                '',
                array(
                    '',
                    '',
                    '',
                    '',
                    '',
                ),
            ),
            array(
                '`foo` TEXT',
                array(
                    '',
                    'foo',
                    'TEXT',
                    '',
                    '',
                ),
            ),
            array(
                '`foo` INT(20)',
                array(
                    '',
                    'foo',
                    'INT',
                    '20',
                    '',
                ),
            ),
            array(
                'IN `fo``fo` INT UNSIGNED',
                array(
                    'IN',
                    'fo`fo',
                    'INT',
                    '',
                    'UNSIGNED',
                ),
            ),
            array(
                'OUT bar VARCHAR(1) CHARSET utf8',
                array(
                    'OUT',
                    'bar',
                    'VARCHAR',
                    '1',
                    'utf8',
                ),
            ),
            array(
                '`"baz\'\'` ENUM(\'a\', \'b\') CHARSET latin1',
                array(
                    '',
                    '"baz\'\'',
                    'ENUM',
                    '\'a\',\'b\'',
                    'latin1',
                ),
            ),
            array(
                'INOUT `foo` DECIMAL(5,2) UNSIGNED ZEROFILL',
                array(
                    'INOUT',
                    'foo',
                    'DECIMAL',
                    '5,2',
                    'UNSIGNED ZEROFILL',
                ),
            ),
            array(
                '`foo``s func` SET(\'test\'\'esc"\',   \'more\\\'esc\')',
                array(
                    '',
                    'foo`s func',
                    'SET',
                    '\'test\'\'esc"\',\'more\\\'esc\'',
                    '',
                ),
            )
        );
    }

    /**
     * @dataProvider getParametersProvider
     *
     * @param mixed $query
     */
    public function testGetParameters($query, array $expected)
    {
        $parser = new Parser($query);
        $this->assertEquals($expected, Routine::getParameters($parser->statements[0]));
    }

    public function getParametersProvider()
    {
        return array(
            array(
                'CREATE PROCEDURE `foo`() SET @A=0',
                array(
                    'num' => 0,
                    'dir' => array(),
                    'name' => array(),
                    'type' => array(),
                    'length' => array(),
                    'length_arr' => array(),
                    'opts' => array(),
                ),
            ),
            array(
                'CREATE DEFINER=`user\\`@`somehost``(` FUNCTION `foo```(`baz` INT) BEGIN SELECT NULL; END',
                array(
                    'num' => 1,
                    'dir' => array(
                        0 => '',
                    ),
                    'name' => array(
                        0 => 'baz',
                    ),
                    'type' => array(
                        0 => 'INT',
                    ),
                    'length' => array(
                        0 => '',
                    ),
                    'length_arr' => array(
                        0 => array(),
                    ),
                    'opts' => array(
                        0 => '',
                    ),
                ),
            ),
            array(
                'CREATE PROCEDURE `foo`(IN `baz\\)` INT(25) zerofill unsigned) BEGIN SELECT NULL; END',
                array(
                    'num' => 1,
                    'dir' => array(
                        0 => 'IN',
                    ),
                    'name' => array(
                        0 => 'baz\\)',
                    ),
                    'type' => array(
                        0 => 'INT',
                    ),
                    'length' => array(
                        0 => '25',
                    ),
                    'length_arr' => array(
                        0 => array('25'),
                    ),
                    'opts' => array(
                        0 => 'UNSIGNED ZEROFILL',
                    ),
                ),
            ),
            array(
                'CREATE PROCEDURE `foo`(IN `baz\\` INT(001) zerofill, out bazz varchar(15) charset utf8) ' .
                'BEGIN SELECT NULL; END',
                array(
                    'num' => 2,
                    'dir' => array(
                        0 => 'IN',
                        1 => 'OUT',
                    ),
                    'name' => array(
                        0 => 'baz\\',
                        1 => 'bazz',
                    ),
                    'type' => array(
                        0 => 'INT',
                        1 => 'VARCHAR',
                    ),
                    'length' => array(
                        0 => '1',
                        1 => '15',
                    ),
                    'length_arr' => array(
                        0 => array('1'),
                        1 => array('15'),
                    ),
                    'opts' => array(
                        0 => 'ZEROFILL',
                        1 => 'utf8',
                    ),
                ),
            )
        );
    }
}
