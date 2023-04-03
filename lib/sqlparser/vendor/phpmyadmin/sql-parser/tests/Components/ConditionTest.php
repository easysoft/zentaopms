<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\Condition;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ConditionTest extends TestCase
{
    public function testParse()
    {
        $component = Condition::parse(new Parser(), $this->getTokensList('/* id = */ id = 10'));
        $this->assertEquals($component[0]->expr, 'id = 10');
    }

    public function testParseBetween()
    {
        $component = Condition::parse(new Parser(), $this->getTokensList('(id BETWEEN 10 AND 20) OR (id BETWEEN 30 AND 40)'));
        $this->assertEquals($component[0]->expr, '(id BETWEEN 10 AND 20)');
        $this->assertEquals($component[1]->expr, 'OR');
        $this->assertEquals($component[2]->expr, '(id BETWEEN 30 AND 40)');
    }
}
