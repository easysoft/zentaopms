<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Components\OrderKeyword;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class OrderKeywordTest extends TestCase
{
    public function testBuild(): void
    {
        $this->assertEquals(
            OrderKeyword::build(
                [
                    new OrderKeyword(new Expression('a'), 'ASC'),
                    new OrderKeyword(new Expression('b'), 'DESC'),
                ]
            ),
            'a ASC, b DESC'
        );
    }
}
