<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\IntoKeyword;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class IntoKeywordTest extends TestCase
{
    public function testParse(): void
    {
        $component = IntoKeyword::parse(new Parser(), $this->getTokensList('OUTFILE "/tmp/outfile.txt"'));
        $this->assertEquals($component->type, 'OUTFILE');
        $this->assertEquals($component->dest, '/tmp/outfile.txt');
    }

    public function testBuild(): void
    {
        $component = IntoKeyword::parse(new Parser(), $this->getTokensList('tbl(`col1`, `col2`)'));
        $this->assertEquals('tbl(`col1`, `col2`)', IntoKeyword::build($component));
    }

    public function testBuildValues(): void
    {
        $component = IntoKeyword::parse(new Parser(), $this->getTokensList('@a1, @a2, @a3'));
        $this->assertEquals('@a1, @a2, @a3', IntoKeyword::build($component));
    }

    public function testBuildOutfile(): void
    {
        $component = IntoKeyword::parse(new Parser(), $this->getTokensList('OUTFILE "/tmp/outfile.txt"'));
        $this->assertEquals('OUTFILE "/tmp/outfile.txt"', IntoKeyword::build($component));
    }

    public function testParseErr1(): void
    {
        $component = IntoKeyword::parse(new Parser(), $this->getTokensList('OUTFILE;'));
        $this->assertEquals($component->type, 'OUTFILE');
    }
}
