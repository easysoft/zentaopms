<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Component;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\TokensList;

class ComponentTest extends TestCase
{
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Not implemented yet.
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testParse()
    {
        Component::parse(new Parser(), new TokensList());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Not implemented yet.
     */
    public function testBuild()
    {
        Component::build(null);
    }
}
