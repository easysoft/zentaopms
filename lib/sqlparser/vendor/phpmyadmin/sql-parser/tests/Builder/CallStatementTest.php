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

    public function testBuilderShort(): void
    {
        $query = 'CALL foo';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query . '()', $stmt->build());
    }

    public function testBuilderWithDbName(): void
    {
        $query = 'CALL mydb.foo()';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }

    public function testBuilderWithDbNameShort(): void
    {
        $query = 'CALL mydb.foo';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query . '()', $stmt->build());
    }

    public function testBuilderWithDbNameAndParams(): void
    {
        $query = 'CALL mydb.foo(@bar, @baz);';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals('CALL mydb.foo(@bar,@baz)', $stmt->build());
    }

    public function testBuilderMultiCallsShort(): void
    {
        $query = 'call e;call f';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals('CALL e()', $stmt->build());
        $stmt = $parser->statements[1];

        $this->assertEquals('CALL f()', $stmt->build());
    }

    public function testBuilderMultiCalls(): void
    {
        $query = 'call e();call f';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals('CALL e()', $stmt->build());
        $stmt = $parser->statements[1];

        $this->assertEquals('CALL f()', $stmt->build());
    }

    public function testBuilderMultiCallsArgs(): void
    {
        $query = 'call e("foo");call f';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals('CALL e("foo")', $stmt->build());
        $stmt = $parser->statements[1];

        $this->assertEquals('CALL f()', $stmt->build());
    }
}
