<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class RestoreStatementTest extends TestCase
{
    /**
     * @dataProvider restoreProvider
     *
     * @param mixed $test
     */
    public function testRestore($test)
    {
        $this->runParserTest($test);
    }

    public function restoreProvider()
    {
        return array(
            array('parser/parseRestore')
        );
    }
}
