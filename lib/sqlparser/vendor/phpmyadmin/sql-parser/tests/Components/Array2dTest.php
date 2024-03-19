<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\Array2d;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class Array2dTest extends TestCase
{
    public function testParse(): void
    {
        $parser = new Parser();
        $arrays = Array2d::parse($parser, $this->getTokensList('(1, 2) +'));
        $this->assertEquals(
            [
                1,
                2,
            ],
            $arrays[0]->values
        );
    }

    public function testBuild(): void
    {
        $arrays = Array2d::parse(new Parser(), $this->getTokensList('(1, 2), (3, 4), (5, 6)'));
        $this->assertEquals(
            '(1, 2), (3, 4), (5, 6)',
            Array2d::build($arrays)
        );
    }

    public function testParseErr1(): void
    {
        $parser = new Parser();
        Array2d::parse($parser, $this->getTokensList('(1, 2 +'));

        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testParseErr2(): void
    {
        $parser = new Parser();
        Array2d::parse($parser, $this->getTokensList('(1, 2 TABLE'));

        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testParseErr3(): void
    {
        $parser = new Parser();
        Array2d::parse($parser, $this->getTokensList(')'));
        $this->assertCount(1, $parser->errors);
        $this->assertEquals(
            'An opening bracket followed by a set of values was expected.',
            $parser->errors[0]->getMessage()
        );
    }

    public function testParseErr4(): void
    {
        $parser = new Parser();
        Array2d::parse($parser, $this->getTokensList('TABLE'));
        $this->assertCount(1, $parser->errors);
        $this->assertEquals(
            'An opening bracket followed by a set of values was expected.',
            $parser->errors[0]->getMessage()
        );
    }

    public function testParseErr5(): void
    {
        $parser = new Parser();
        Array2d::parse($parser, $this->getTokensList('(1, 2),'));
        $this->assertCount(1, $parser->errors);
        $this->assertEquals(
            'An opening bracket followed by a set of values was expected.',
            $parser->errors[0]->getMessage()
        );
    }

    public function testParseErr6(): void
    {
        $parser = new Parser();
        Array2d::parse($parser, $this->getTokensList('(1, 2),(3)'));
        $this->assertCount(1, $parser->errors);
        $this->assertEquals(
            '2 values were expected, but found 1.',
            $parser->errors[0]->getMessage()
        );
    }
}
