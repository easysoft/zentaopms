<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class CreateStatementTest extends TestCase
{
    /**
     * @dataProvider createProvider
     *
     * @param mixed $test
     */
    public function testCreate($test)
    {
        $this->runParserTest($test);
    }

    public function createProvider()
    {
        return array(
            array('parser/parseCreateDatabase'),
            array('parser/parseCreateDatabaseErr'),
            array('parser/parseCreateFunction'),
            array('parser/parseCreateFunctionErr1'),
            array('parser/parseCreateFunctionErr2'),
            array('parser/parseCreateFunctionErr3'),
            array('parser/parseCreateProcedure'),
            array('parser/parseCreateProcedure1'),
            array('parser/parseCreateProcedure2'),
            array('parser/parseCreateSchema'),
            array('parser/parseCreateSchemaErr'),
            array('parser/parseCreateTable'),
            array('parser/parseCreateTable2'),
            array('parser/parseCreateTable3'),
            array('parser/parseCreateTable4'),
            array('parser/parseCreateTable5'),
            array('parser/parseCreateTable6'),
            array('parser/parseCreateTable7'),
            array('parser/parseCreateTableErr1'),
            array('parser/parseCreateTableErr2'),
            array('parser/parseCreateTableErr3'),
            array('parser/parseCreateTableErr4'),
            array('parser/parseCreateTableErr5'),
            array('parser/parseCreateTableSelect'),
            array('parser/parseCreateTableAsSelect'),
            array('parser/parseCreateTableLike'),
            array('parser/parseCreateTableSpatial'),
            array('parser/parseCreateTableTimestampWithPrecision'),
            array('parser/parseCreateTableEnforcedCheck'),
            array('parser/parseCreateTableNotEnforcedCheck'),
            array('parser/parseCreateTableWithInvisibleKey'),
            array('parser/parseCreateTrigger'),
            array('parser/parseCreateUser'),
            array('parser/parseCreateView'),
            array('parser/parseCreateView2'),
            array('parser/parseCreateView3'),
            array('parser/parseCreateView4'),
            array('parser/parseCreateViewMultiple'),
            array('parser/parseCreateViewWithoutQuotes'),
            array('parser/parseCreateViewWithQuotes'),
            array('parser/parseCreateViewWithWrongSyntax'),
            array('parser/parseCreateViewWithUnion'),
            array('parser/parseCreateViewAsWithAs'),
        );
    }
}
