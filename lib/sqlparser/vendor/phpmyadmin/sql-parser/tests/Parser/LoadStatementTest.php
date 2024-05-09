<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class LoadStatementTest extends TestCase
{
    public function testLoadOptions(): void
    {
        $data = $this->getData('parser/parseLoad1');
        $parser = new Parser($data['query']);
        $stmt = $parser->statements[0];
        $this->assertEquals(10, $stmt->options->has('CONCURRENT'));
    }

    /**
     * @dataProvider loadProvider
     */
    public function testLoad(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function loadProvider(): array
    {
        return [
            ['parser/parseLoad1'],
            ['parser/parseLoad2'],
            ['parser/parseLoad3'],
            ['parser/parseLoad4'],
            ['parser/parseLoad5'],
            ['parser/parseLoad6'],
            ['parser/parseLoad7'],
            ['parser/parseLoadErr1'],
            ['parser/parseLoadErr2'],
            ['parser/parseLoadErr3'],
            ['parser/parseLoadErr4'],
            ['parser/parseLoadErr5'],
            ['parser/parseLoadErr6'],
        ];
    }
}
