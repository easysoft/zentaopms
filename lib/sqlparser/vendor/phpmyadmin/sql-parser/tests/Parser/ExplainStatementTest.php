<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class ExplainStatementTest extends TestCase
{
    /**
     * @dataProvider explainProvider
     *
     * @param mixed $test
     */
    public function testExplain($test)
    {
        $this->runParserTest($test);
    }

    public function explainProvider()
    {
        return array(
            array('parser/parseExplain')
        );
    }
}
