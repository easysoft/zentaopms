<?php

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class TruncateStatementTest extends TestCase
{
    public function testBuilder()
    {
        $query = 'TRUNCATE TABLE mytable;';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }

    public function testBuilderDbtable()
    {
        $query = 'TRUNCATE TABLE mydb.mytable;';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }

    public function testBuilderDbtableBackQuotes()
    {
        $query = 'TRUNCATE TABLE `mydb`.`mytable`;';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }

}
