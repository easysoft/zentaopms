<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ExplainStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        /* Assertion 1 */
        $query = 'EXPLAIN SELECT * FROM test;';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'EXPLAIN SELECT * FROM test',
            $stmt->build()
        );

        /* Assertion 2 */
        $query = 'EXPLAIN ANALYZE SELECT * FROM tablename;';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'EXPLAIN ANALYZE SELECT * FROM tablename',
            $stmt->build()
        );

        /* Assertion 3 */
        $query = 'DESC ANALYZE SELECT * FROM tablename;';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'DESC ANALYZE SELECT * FROM tablename',
            $stmt->build()
        );

        /* Assertion 4 */
        $query = 'ANALYZE SELECT * FROM tablename;';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'ANALYZE SELECT * FROM tablename',
            $stmt->build()
        );

        /* Assertion 5 */
        $query = 'DESCRIBE tablename;';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'DESCRIBE `tablename`',
            $stmt->build()
        );

        /* Assertion 6 */
        $query = 'DESC FOR CONNECTION 458';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'DESC FOR CONNECTION 458',
            $stmt->build()
        );

        /* Assertion 7 */
        $query = 'EXPLAIN FORMAT=TREE SELECT * FROM db;';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'EXPLAIN FORMAT=TREE SELECT * FROM db',
            $stmt->build()
        );

        /* Assertion 8 */
        $query = 'DESCRIBE tablename colname;';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'DESCRIBE `tablename` `colname`',
            $stmt->build()
        );

        /* Assertion 9 */
        $query = 'DESCRIBE tablename \'col%me\';';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'DESCRIBE `tablename` `col%me`',
            $stmt->build()
        );

        /* Assertion 9 */
        $query = 'DESCRIBE db.tablename \'col%me\';';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'DESCRIBE `db`.`tablename` `col%me`',
            $stmt->build()
        );
    }
}
