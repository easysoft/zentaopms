<?php

namespace PhpMyAdmin\SqlParser\Tests\Utils;

use PhpMyAdmin\SqlParser\Lexer;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Utils\Error;

class ErrorTest extends TestCase
{
    public function testGet()
    {
        $lexer = new Lexer('SELECT * FROM db..tbl }');
        $parser = new Parser($lexer->list);
        $this->assertEquals(
            array(
                array(
                    'Unexpected character.',
                    0,
                    '}',
                    22,
                ),
                array(
                    'Unexpected dot.',
                    0,
                    '.',
                    17,
                ),
            ),
            Error::get(array($lexer, $parser))
        );
    }

    public function testFormat()
    {
        $this->assertEquals(
            array('#1: error msg (near "token" at position 100)'),
            Error::format(array(array('error msg', 42, 'token', 100)))
        );
        $this->assertEquals(
            array('#1: error msg (near "token" at position 100)', '#2: error msg (near "token" at position 200)'),
            Error::format(array(array('error msg', 42, 'token', 100), array('error msg', 42, 'token', 200)))
        );
    }
}
