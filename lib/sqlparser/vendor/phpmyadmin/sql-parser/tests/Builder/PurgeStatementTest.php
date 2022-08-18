<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class PurgeStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        $query = 'PURGE BINARY LOGS TO \'mysql-bin.010\'';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals($query, $stmt->build());

        $query = 'PURGE BINARY LOGS BEFORE \'2008-04-02 22:46:26\'';
        $parser = new Parser($query);
        $stmt = $parser->statements[0];
        $this->assertEquals($query, $stmt->build());
    }
}
