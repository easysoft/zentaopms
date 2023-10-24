<?php

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ReplaceStatementTest extends TestCase
{
    public function testBuilder()
    {
        $parser = new Parser(
            'REPLACE INTO tbl(col1, col2, col3) VALUES (1, "str", 3.14)'
        );
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'REPLACE INTO tbl(`col1`, `col2`, `col3`) VALUES (1, "str", 3.14)',
            $stmt->build()
        );
    }

    public function testBuilderSet()
    {
        $parser = new Parser(
            'REPLACE INTO tbl(col1, col2, col3) SET col1=1, col2="str", col3=3.14'
        );
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'REPLACE INTO tbl(`col1`, `col2`, `col3`) SET col1 = 1, col2 = "str", col3 = 3.14',
            $stmt->build()
        );
    }

    public function testBuilderSelect()
    {
        $parser = new Parser(
            'REPLACE INTO tbl(col1, col2, col3) SELECT col1, col2, col3 FROM tbl2'
        );
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'REPLACE INTO tbl(`col1`, `col2`, `col3`) SELECT col1, col2, col3 FROM tbl2',
            $stmt->build()
        );
    }

    public function testBuilderSelectDelayed()
    {
        $parser = new Parser(
            'REPLACE DELAYED INTO tbl(col1, col2, col3) SELECT col1, col2, col3 FROM tbl2'
        );
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'REPLACE DELAYED INTO tbl(`col1`, `col2`, `col3`) SELECT col1, col2, col3 FROM tbl2',
            $stmt->build()
        );
    }
}
