<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Component;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\TokensList;
use Throwable;

class ComponentTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testParse(): void
    {
        $this->expectExceptionMessage('Not implemented yet.');
        $this->expectException(Throwable::class);
        Component::parse(new Parser(), new TokensList());
    }

    public function testBuild(): void
    {
        $this->expectExceptionMessage('Not implemented yet.');
        $this->expectException(Throwable::class);
        Component::build(null);
    }
}
