<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class ExplainStatementTest extends TestCase
{
    public function testBuilderView(): void
    {
        $query = 'EXPLAIN SELECT * FROM test;';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            ' EXPLAIN SELECT * FROM test',
            $stmt->build()
        );
    }
}
