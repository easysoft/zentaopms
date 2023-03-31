<?php

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class SetStatementTest extends TestCase
{
    public function testBuilderView()
    {
        /* Assertion 1 */
        $query = 'SET CHARACTER SET \'utf8\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );

        /* Assertion 2 */
        $query = 'SET CHARSET \'utf8\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );

        /* Assertion 3 */
        $query = 'SET NAMES \'utf8\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            $query,
            $stmt->build()
        );

        /* Assertion 4 */
        $query = 'SET NAMES \'utf8\' COLLATE \'utf8_general_ci\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET NAMES \'utf8\'  COLLATE \'utf8_general_ci\'',
            $stmt->build()
        );

        /* Assertion 5 */
        $query = 'SET NAMES \'utf8\' DEFAULT';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET NAMES \'utf8\'  DEFAULT',
            $stmt->build()
        );

        /* Assertion 6 */
        $query = 'SET sql_mode = \'TRADITIONAL\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET  sql_mode = \'TRADITIONAL\'',
            $stmt->build()
        );

        /* Assertion 7 */
        $query = 'SET SESSION   sql_mode = \'TRADITIONAL\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET SESSION sql_mode = \'TRADITIONAL\'',
            $stmt->build()
        );

        /* Assertion 8 */
        $query = 'SET GLOBAL   sql_mode = \'TRADITIONAL\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET GLOBAL sql_mode = \'TRADITIONAL\'',
            $stmt->build()
        );

        /* Assertion 9 */
        $query = 'SET @@SESSION   sql_mode = \'TRADITIONAL\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET SESSION sql_mode = \'TRADITIONAL\'',
            $stmt->build()
        );

        /* Assertion 10 */
        $query = 'SET @@GLOBAL   sql_mode = \'TRADITIONAL\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET GLOBAL sql_mode = \'TRADITIONAL\'',
            $stmt->build()
        );

        /* Assertion 11 */
        $query = 'SET @@sql_mode = \'TRADITIONAL\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET  @@sql_mode = \'TRADITIONAL\'',
            $stmt->build()
        );

        /* Assertion 12 */
        $query = 'SET PERSIST sql_mode = \'TRADITIONAL\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET PERSIST sql_mode = \'TRADITIONAL\'',
            $stmt->build()
        );

        /* Assertion 13 */
        $query = 'SET PERSIST_ONLY sql_mode = \'TRADITIONAL\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET PERSIST_ONLY sql_mode = \'TRADITIONAL\'',
            $stmt->build()
        );

        /* Assertion 14 */
        $query = 'SET @@PERSIST sql_mode = \'TRADITIONAL\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET PERSIST sql_mode = \'TRADITIONAL\'',
            $stmt->build()
        );

        /* Assertion 15 */
        $query = 'SET @@PERSIST_ONLY sql_mode = \'TRADITIONAL\'';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'SET PERSIST_ONLY sql_mode = \'TRADITIONAL\'',
            $stmt->build()
        );
    }
}
