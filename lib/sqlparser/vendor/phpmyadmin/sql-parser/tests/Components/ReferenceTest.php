<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Components\Reference;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ReferenceTest extends TestCase
{
    public function testParse(): void
    {
        $component = Reference::parse(new Parser(), $this->getTokensList('tbl (id)'));
        $this->assertEquals('tbl', $component->table->table);
        $this->assertEquals(['id'], $component->columns);
    }

    public function testBuild(): void
    {
        $component = new Reference(new Expression('`tbl`'), ['id']);
        $this->assertEquals('`tbl` (`id`)', Reference::build($component));
    }
}
