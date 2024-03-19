<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class CreateStatementTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function createProvider(): array
    {
        return [
            ['parser/parseCreateDatabase'],
            ['parser/parseCreateDatabaseErr'],
            ['parser/parseCreateFunction'],
            ['parser/parseCreateFunctionErr1'],
            ['parser/parseCreateFunctionErr2'],
            ['parser/parseCreateFunctionErr3'],
            ['parser/parseCreateProcedure'],
            ['parser/parseCreateProcedure1'],
            ['parser/parseCreateProcedure2'],
            ['parser/parseCreateProcedure3'],
            ['parser/parseCreateProcedure4'],
            ['parser/parseCreateSchema'],
            ['parser/parseCreateSchemaErr'],
            ['parser/parseCreateTable'],
            ['parser/parseCreateTable2'],
            ['parser/parseCreateTable3'],
            ['parser/parseCreateTable4'],
            ['parser/parseCreateTable5'],
            ['parser/parseCreateTable6'],
            ['parser/parseCreateTable7'],
            ['parser/parseCreateTable8'],
            ['parser/parseCreateTable9'],
            ['parser/parseCreateTable10'],
            ['parser/parseCreateTable11'],
            ['parser/parseCreateTable12'],
            ['parser/parseCreateTable13'],
            ['parser/parseCreateTable14'],
            ['parser/parseCreateTable15'],
            ['parser/parseCreateTable16'],
            ['parser/parseCreateTable17'],
            ['parser/parseCreateTable18'],
            ['parser/parseCreateTableErr1'],
            ['parser/parseCreateTableErr2'],
            ['parser/parseCreateTableErr3'],
            ['parser/parseCreateTableErr4'],
            ['parser/parseCreateTableErr5'],
            ['parser/parseCreateTableSelect'],
            ['parser/parseCreateTableAsSelect'],
            ['parser/parseCreateTableLike'],
            ['parser/parseCreateTableSpatial'],
            ['parser/parseCreateTableTimestampWithPrecision'],
            ['parser/parseCreateTableEnforcedCheck'],
            ['parser/parseCreateTableNotEnforcedCheck'],
            ['parser/parseCreateTableWithInvisibleKey'],
            ['parser/parseCreateTrigger'],
            ['parser/parseCreateUser1'],
            ['parser/parseCreateUser2'],
            ['parser/parseCreateView'],
            ['parser/parseCreateView2'],
            ['parser/parseCreateView3'],
            ['parser/parseCreateView4'],
            ['parser/parseCreateView5'],
            ['parser/parseCreateViewMultiple'],
            ['parser/parseCreateViewWithoutQuotes'],
            ['parser/parseCreateViewWithQuotes'],
            ['parser/parseCreateViewWithWrongSyntax'],
            ['parser/parseCreateViewWithUnion'],
            ['parser/parseCreateViewAsWithAs'],
            ['parser/parseCreateOrReplaceView1'],
        ];
    }
}
