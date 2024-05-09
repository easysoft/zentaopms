<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Misc;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class BugsTest extends TestCase
{
    /**
     * @dataProvider bugProvider
     */
    public function testBug(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function bugProvider(): array
    {
        return [
            ['bugs/fuzz1'],
            ['bugs/fuzz2'],
            ['bugs/fuzz3'],
            ['bugs/fuzz4'],
            ['bugs/gh9'],
            ['bugs/gh14'],
            ['bugs/gh16'],
            ['bugs/gh202'],
            ['bugs/gh234'],
            ['bugs/gh317'],
            ['bugs/gh412'],
            ['bugs/gh478'],
            ['bugs/gh492'],
            ['bugs/gh496'],
            ['bugs/gh498'],
            ['bugs/gh499'],
            ['bugs/gh508'],
            ['bugs/gh511'],
            ['bugs/pma11800'],
            ['bugs/pma11836'],
            ['bugs/pma11843'],
            ['bugs/pma11879'],
        ];
    }
}
