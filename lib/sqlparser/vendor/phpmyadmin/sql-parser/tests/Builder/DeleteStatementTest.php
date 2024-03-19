<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class DeleteStatementTest extends TestCase
{
    public function testBuilderSingleTable(): void
    {
        /* Assertion 1 */
        $query = 'DELETE IGNORE FROM t1';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 2 */
        $query = 'DELETE IGNORE FROM t1 WHERE 1=1';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 3 */
        $query = 'DELETE IGNORE FROM t1 WHERE 1=1 ORDER BY id ASC';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 4 */
        $query = 'DELETE IGNORE FROM t1 WHERE 1=1 ORDER BY id ASC LIMIT 0, 25';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 5 */
        $query = 'DELETE IGNORE FROM t1';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 6 */
        $query = 'DELETE LOW_PRIORITY FROM `test`.users '
            . 'WHERE `id`<3 AND (username="Dan" OR username="Paul") ORDER BY id ASC';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }

    public function testBuilderMultiTable(): void
    {
        /* Assertion 1 */
        $query = 'DELETE QUICK table1, table2.* FROM table1 AS `t1`, table2 AS `t2`';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 2 */
        $query = 'DELETE QUICK table1, table2.* FROM table1 AS `t1`, table2 AS `t2` WHERE 1=1';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 3 */
        $query = 'DELETE QUICK FROM table1, table2.* USING table1 AS `t1`, table2 AS `t2` WHERE 1=1';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());

        /* Assertion 4 */
        $query = 'DELETE LOW_PRIORITY t1, t2 FROM t1 INNER JOIN t2 '
            . 'INNER JOIN t3 WHERE t1.id=t2.id AND t2.id=t3.id';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }
}
