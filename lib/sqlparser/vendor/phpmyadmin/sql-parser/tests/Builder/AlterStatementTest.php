<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class AlterStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        $query = 'ALTER TABLE `actor` ' .
            'ADD PRIMARY KEY (`actor_id`), ' .
            'ADD KEY `idx_actor_last_name` (`last_name`)';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }
}
