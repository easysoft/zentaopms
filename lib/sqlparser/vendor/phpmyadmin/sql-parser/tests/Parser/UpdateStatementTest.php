<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class UpdateStatementTest extends TestCase
{
    /**
     * @dataProvider updateProvider
     *
     * @param mixed $test
     */
    public function testUpdate($test)
    {
        $this->runParserTest($test);
    }

    public function updateProvider()
    {
        return array(
            array('parser/parseUpdate'),
            array('parser/parseUpdate2'),
            array('parser/parseUpdate3'),
            array('parser/parseUpdateErr')
        );
    }
}
