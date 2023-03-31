<?php

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class RenameStatementTest extends TestCase
{
    public function testBuilder()
    {
        $query = 'RENAME TABLE old_table TO new_table';
        $parser = new Parser(
            $query
        );
        $stmt = $parser->statements[0];
        $this->assertEquals(
            $query,
            $stmt->build()
        );

        $query = 'RENAME TABLE current_db.tbl_name TO other_db.tbl_name';
        $parser = new Parser(
            $query
        );
        $stmt = $parser->statements[0];
        $this->assertEquals(
            $query,
            $stmt->build()
        );

        $query = 'RENAME TABLE old_table1 TO new_table1, old_table2 TO new_table2, old_table3 TO new_table3';
        $parser = new Parser(
            $query
        );
        $stmt = $parser->statements[0];
        $this->assertEquals(
            $query,
            $stmt->build()
        );
    }
}
