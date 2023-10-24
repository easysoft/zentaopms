<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class InsertStatementTest extends TestCase
{
    /**
     * @dataProvider insertProvider
     *
     * @param mixed $test
     */
    public function testInsert($test)
    {
        $this->runParserTest($test);
    }

    public function insertProvider()
    {
        return array(
            array('parser/parseInsert'),
            array('parser/parseInsertSelect'),
            array('parser/parseInsertOnDuplicateKey'),
            array('parser/parseInsertSetOnDuplicateKey'),
            array('parser/parseInsertSelectOnDuplicateKey'),
            array('parser/parseInsertOnDuplicateKeyErr'),
            array('parser/parseInsertErr'),
            array('parser/parseInsertErr2'),
            array('parser/parseInsertIntoErr')
        );
    }
}
