<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class LockStatementTest extends TestCase
{
    /**
     * @dataProvider lockProvider
     *
     * @param mixed $test
     */
    public function testLock($test)
    {
        $this->runParserTest($test);
    }

    public function lockProvider()
    {
        return array(
            array('parser/parseLock1'),
            array('parser/parseLock2'),
            array('parser/parseLock3'),
            array('parser/parseLock4'),
            array('parser/parseLock5'),
            array('parser/parseLockErr1'),
            array('parser/parseLockErr2'),
            array('parser/parseLockErr3'),
            array('parser/parseLockErr4'),
            array('parser/parseLockErr5'),
            array('parser/parseLockErr6'),
            array('parser/parseLockErr7'),
            array('parser/parseLockErr8'),
            array('parser/parseLockErr9'),
            array('parser/parseLockErr10'),
            array('parser/parseUnlock1'),
            array('parser/parseUnlockErr1')
        );
    }
}
