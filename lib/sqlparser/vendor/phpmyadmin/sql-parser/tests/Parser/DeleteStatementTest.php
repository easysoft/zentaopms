<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class DeleteStatementTest extends TestCase
{
    /**
     * @dataProvider deleteProvider
     *
     * @param mixed $test
     */
    public function testDelete($test)
    {
        $this->runParserTest($test);
    }

    public function deleteProvider()
    {
        return array(
            array('parser/parseDelete'),
            array('parser/parseDelete2'),
            array('parser/parseDelete3'),
            array('parser/parseDelete4'),
            array('parser/parseDelete5'),
            array('parser/parseDelete6'),
            array('parser/parseDelete7'),
            array('parser/parseDelete8'),
            array('parser/parseDelete9'),
            array('parser/parseDelete10'),
            array('parser/parseDelete11'),
            array('parser/parseDelete12'),
            array('parser/parseDeleteErr1'),
            array('parser/parseDeleteErr2'),
            array('parser/parseDeleteErr3'),
            array('parser/parseDeleteErr4'),
            array('parser/parseDeleteErr5'),
            array('parser/parseDeleteErr6'),
            array('parser/parseDeleteErr7'),
            array('parser/parseDeleteErr8'),
            array('parser/parseDeleteErr9'),
            array('parser/parseDeleteErr10'),
            array('parser/parseDeleteErr11'),
            array('parser/parseDeleteErr12'),
            array('parser/parseDeleteJoin')
        );
    }
}
