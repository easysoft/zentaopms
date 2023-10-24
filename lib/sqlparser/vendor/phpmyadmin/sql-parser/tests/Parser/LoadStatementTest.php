<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class LoadStatementTest extends TestCase
{
    public function testLoadOptions()
    {
        $data = $this->getData('parser/parseLoad1');
        $parser = new Parser($data['query']);
        $stmt = $parser->statements[0];
        $this->assertEquals(10, $stmt->options->has('CONCURRENT'));
    }

    /**
     * @dataProvider loadProvider
     *
     * @param mixed $test
     */
    public function testLoad($test)
    {
        $this->runParserTest($test);
    }

    public function loadProvider()
    {
        return array(
            array('parser/parseLoad1'),
            array('parser/parseLoad2'),
            array('parser/parseLoad3'),
            array('parser/parseLoad4'),
            array('parser/parseLoad5'),
            array('parser/parseLoad6'),
            array('parser/parseLoadErr1'),
            array('parser/parseLoadErr2'),
            array('parser/parseLoadErr3'),
            array('parser/parseLoadErr4'),
            array('parser/parseLoadErr5'),
            array('parser/parseLoadErr6')
        );
    }
}
