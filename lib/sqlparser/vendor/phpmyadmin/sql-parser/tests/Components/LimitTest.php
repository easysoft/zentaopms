<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\Limit;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class LimitTest extends TestCase
{
    public function testBuildWithoutOffset()
    {
        $component = new Limit(1);
        $this->assertEquals(Limit::build($component), '0, 1');
    }

    public function testBuildWithOffset()
    {
        $component = new Limit(1, 2);
        $this->assertEquals(Limit::build($component), '2, 1');
    }

    /**
     * @dataProvider parseProvider
     *
     * @param mixed $test
     */
    public function testParse($test)
    {
        $this->runParserTest($test);
    }

    public function parseProvider()
    {
        return array(
            array('parser/parseLimitErr1'),
            array('parser/parseLimitErr2')
        );
    }
}
