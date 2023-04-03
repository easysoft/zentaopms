<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class TransactionStatementTest extends TestCase
{
    /**
     * @dataProvider transactionProvider
     *
     * @param mixed $test
     */
    public function testTransaction($test)
    {
        $this->runParserTest($test);
    }

    public function transactionProvider()
    {
        return array(
            array('parser/parseTransaction'),
            array('parser/parseTransaction2'),
            array('parser/parseTransaction3'),
            array('parser/parseTransactionErr1')
        );
    }
}
