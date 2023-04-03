<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\ParameterDefinition;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ParameterDefinitionTest extends TestCase
{
    public function testParse()
    {
        $component = ParameterDefinition::parse(
            new Parser(),
            $this->getTokensList('(a INT, b INT')
        );
        $this->assertEquals('a', $component[0]->name);
        $this->assertEquals('b', $component[1]->name);
    }

    public function testParseComplex()
    {
        $parser = new Parser();
        $component = ParameterDefinition::parse(
            $parser,
            $this->getTokensList('CREATE DEFINER=`root`@`%` PROCEDURE `foo`( $bar int )')
        );
        $this->assertEquals('$bar', $component[0]->name);
    }
}
