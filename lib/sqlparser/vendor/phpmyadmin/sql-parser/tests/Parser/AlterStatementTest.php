<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class AlterStatementTest extends TestCase
{
    /**
     * @dataProvider alterProvider
     *
     * @param mixed $test
     */
    public function testAlter($test)
    {
        $this->runParserTest($test);
    }

    public function alterProvider()
    {
        return array(
            array('parser/parseAlter'),
            array('parser/parseAlter2'),
            array('parser/parseAlter3'),
            array('parser/parseAlter4'),
            array('parser/parseAlter5'),
            array('parser/parseAlter6'),
            array('parser/parseAlter7'),
            array('parser/parseAlter8'),
            array('parser/parseAlter9'),
            array('parser/parseAlter10'),
            array('parser/parseAlter11'),
            array('parser/parseAlter12'),
            array('parser/parseAlter13'),
            array('parser/parseAlterErr'),
            array('parser/parseAlterErr2'),
            array('parser/parseAlterErr3'),
            array('parser/parseAlterErr4'),
            array('parser/parseAlterWithInvisible'),
            array('parser/parseAlterTableCharacterSet1'),
            array('parser/parseAlterTableCharacterSet2'),
            array('parser/parseAlterTableCharacterSet3'),
            array('parser/parseAlterTableCharacterSet4'),
            array('parser/parseAlterTableCharacterSet5'),
            array('parser/parseAlterTableCharacterSet6'),
            array('parser/parseAlterTableCharacterSet7'),
            array('parser/parseAlterUser'),
            array('parser/parseAlterUser1'),
            array('parser/parseAlterUser2'),
            array('parser/parseAlterUser3'),
            array('parser/parseAlterUser4'),
            array('parser/parseAlterUser5'),
            array('parser/parseAlterUser6'),
            array('parser/parseAlterUser7'),
            array('parser/parseAlterUser8'),
        );
    }
}
