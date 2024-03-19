<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class ExplainStatementTest extends TestCase
{
    /**
     * @dataProvider explainProvider
     */
    public function testExplain(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function explainProvider(): array
    {
        return [
            ['parser/parseExplain'],
            ['parser/parseExplain1'],
            ['parser/parseExplain2'],
            ['parser/parseExplain3'],
            ['parser/parseExplain4'],
            ['parser/parseExplain5'],
            ['parser/parseExplain6'],
            ['parser/parseExplain7'],
            ['parser/parseExplain8'],
            ['parser/parseExplain9'],
            ['parser/parseExplain10'],
            ['parser/parseExplain11'],
            ['parser/parseExplain12'],
            ['parser/parseExplain13'],
            ['parser/parseExplain14'],
            ['parser/parseExplainErr'],
            ['parser/parseExplainErr1'],
            ['parser/parseExplainErr2'],
            ['parser/parseExplainErr3'],
        ];
    }
}
