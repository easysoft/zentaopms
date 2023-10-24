<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class PurgeStatementTest extends TestCase
{
    /**
     * @dataProvider purgeProvider
     *
     * @param mixed $test
     */
    public function testPurge($test)
    {
        $this->runParserTest($test);
    }

    public function purgeProvider()
    {
        return array(
            array('parser/parsePurge'),
            array('parser/parsePurge2'),
            array('parser/parsePurge3'),
            array('parser/parsePurge4'),
            array('parser/parsePurgeErr'),
            array('parser/parsePurgeErr2'),
            array('parser/parsePurgeErr3')
        );
    }
}
