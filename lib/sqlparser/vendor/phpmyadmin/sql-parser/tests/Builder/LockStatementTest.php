<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class LockStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        /* Assertion 1 */
        $query = 'LOCK TABLES table1 AS `t1` READ LOCAL';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 2 */
        $query = 'LOCK TABLES table1 AS `t1` READ';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 3 */
        $query = 'LOCK TABLES table1 AS `t1` LOW_PRIORITY WRITE';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 4 */
        $query = 'LOCK TABLES table1 AS `t1` WRITE';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 5 */
        $query = 'LOCK TABLES table1 AS `t1` READ LOCAL, table2 AS `t2` WRITE';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 6 */
        $query = 'LOCK TABLES table1 READ LOCAL, table2 AS `t2` WRITE';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 7 */
        $query = 'UNLOCK TABLES';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }
}
