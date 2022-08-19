<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Builder;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class CallStatementTest extends TestCase
{
    public function testBuilder(): void
    {
        $query = 'CALL foo()';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }
}
