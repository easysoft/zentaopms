<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Components\GroupKeyword;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class GroupKeywordTest extends TestCase
{
    public function testBuild()
    {
        $this->assertEquals(
            GroupKeyword::build(
                array(
                    new GroupKeyword(new Expression('a')),
                    new GroupKeyword(new Expression('b')),
                    new GroupKeyword(new Expression('c')),
                )
            ),
            'a, b, c'
        );
    }
}
