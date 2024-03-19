<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class InsertStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        /* Assertion 1 */
        $parser = new Parser('INSERT INTO tbl(`col1`, `col2`, `col3`) VALUES (1, "str", 3.14)');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'INSERT INTO tbl(`col1`, `col2`, `col3`) VALUES (1, "str", 3.14)',
            $stmt->build()
        );

        /* Assertion 2 */
        /* Reserved keywords (with backquotes as field name) */
        $parser = new Parser('INSERT INTO tbl(`order`) VALUES (1)');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'INSERT INTO tbl(`order`) VALUES (1)',
            $stmt->build()
        );

        /* Assertion 3 */
        /* INSERT ... SET ... */
        $parser = new Parser('INSERT INTO tbl SET FOO = 1');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'INSERT INTO tbl SET FOO = 1',
            $stmt->build()
        );

        /* Assertion 4 */
        /* INSERT ... SELECT ... */
        $parser = new Parser('INSERT INTO tbl SELECT * FROM bar');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'INSERT INTO tbl SELECT * FROM bar',
            $stmt->build()
        );

        /* Assertion 5 */
        /* INSERT ... ON DUPLICATE KEY UPDATE ... */
        $parser = new Parser('INSERT INTO tbl SELECT * FROM bar ON DUPLICATE KEY UPDATE baz = 1');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'INSERT INTO tbl SELECT * FROM bar ON DUPLICATE KEY UPDATE baz = 1',
            $stmt->build()
        );

        /* Assertion 6 */
        /* INSERT [OPTIONS] INTO ... */
        $parser = new Parser('INSERT DELAYED IGNORE INTO tbl SELECT * FROM bar');
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'INSERT DELAYED IGNORE INTO tbl SELECT * FROM bar',
            $stmt->build()
        );
    }
}
