<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class TransactionStatementTest extends TestCase
{
    public function testBuildWithoutEnd(): void
    {
        $data = $this->getData('parser/parseTransaction4');
        $parser = new Parser($data['query']);
        $stmt = $parser->statements[0];
        $this->assertEquals(
            'START TRANSACTION;SET  time_zone = "+00:00";',
            $stmt->build()
        );
    }

    /**
     * @dataProvider transactionProvider
     */
    public function testTransaction(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function transactionProvider(): array
    {
        return [
            ['parser/parseTransaction'],
            ['parser/parseTransaction2'],
            ['parser/parseTransaction3'],
            ['parser/parseTransaction4'],
            ['parser/parseTransaction5'],
            ['parser/parseTransaction6'],
            ['parser/parseTransaction7'],
            ['parser/parseTransactionErr1'],
        ];
    }
}
