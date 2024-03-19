<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class TransactionStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        $query = 'START TRANSACTION;' .
            'SELECT @A:=SUM(salary) FROM table1 WHERE type=1;' .
            'UPDATE table2 SET summary=@A WHERE type=1;' .
            'COMMIT;';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            'START TRANSACTION;' .
            'SELECT @A:=SUM(salary) FROM table1 WHERE type=1;' .
            'UPDATE table2 SET summary = @A WHERE type=1;' .
            'COMMIT',
            $stmt->build()
        );
    }
}
