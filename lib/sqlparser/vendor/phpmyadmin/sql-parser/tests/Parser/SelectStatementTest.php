<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class SelectStatementTest extends TestCase
{
    public function testSelectOptions(): void
    {
        $data = $this->getData('parser/parseSelect');
        $parser = new Parser($data['query']);
        $stmt = $parser->statements[0];
        $this->assertEquals(10, $stmt->options->has('MAX_STATEMENT_TIME'));
    }

    /**
     * @dataProvider selectProvider
     */
    public function testSelect(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public function selectProvider(): array
    {
        return [
            ['parser/parseSelect2'],
            ['parser/parseSelect3'],
            ['parser/parseSelect4'],
            ['parser/parseSelect5'],
            ['parser/parseSelect6'],
            ['parser/parseSelect7'],
            ['parser/parseSelect8'],
            ['parser/parseSelect9'],
            ['parser/parseSelect10'],
            ['parser/parseSelect11'],
            ['parser/parseSelect12'],
            ['parser/parseSelect13'],
            ['parser/parseSelect14'],
            ['parser/parseSelect15'],
            ['parser/parseSelect16'],
            ['parser/parseSelectAggregateWithPartitionAndAlias'],
            ['parser/parseSelectErr1'],
            ['parser/parseSelectErr2'],
            ['parser/parseSelectNested'],
            ['parser/parseSelectCase1'],
            ['parser/parseSelectCase2'],
            ['parser/parseSelectCase3'],
            ['parser/parseSelectCase4'],
            ['parser/parseSelectCase5'],
            ['parser/parseSelectCaseErr1'],
            ['parser/parseSelectCaseErr2'],
            ['parser/parseSelectCaseErr3'],
            ['parser/parseSelectCaseErr4'],
            ['parser/parseSelectCaseErr5'],
            ['parser/parseSelectCaseAlias1'],
            ['parser/parseSelectCaseAlias2'],
            ['parser/parseSelectCaseAlias3'],
            ['parser/parseSelectCaseAlias4'],
            ['parser/parseSelectCaseAlias5'],
            ['parser/parseSelectCaseAlias6'],
            ['parser/parseSelectCaseAliasErr1'],
            ['parser/parseSelectCaseAliasErr2'],
            ['parser/parseSelectCaseAliasErr3'],
            ['parser/parseSelectCaseAliasErr4'],
            ['parser/parseSelectExists'],
            ['parser/parseSelectIntoOptions1'],
            ['parser/parseSelectIntoOptions2'],
            ['parser/parseSelectIntoOptions3'],
            ['parser/parseSelectJoinCross'],
            ['parser/parseSelectJoinNatural'],
            ['parser/parseSelectJoinNaturalLeft'],
            ['parser/parseSelectJoinNaturalRight'],
            ['parser/parseSelectJoinNaturalLeftOuter'],
            ['parser/parseSelectJoinNaturalRightOuter'],
            ['parser/parseSelectJoinMultiple'],
            ['parser/parseSelectJoinMultiple2'],
            ['parser/parseSelectWrongOrder'],
            ['parser/parseSelectWrongOrder2'],
            ['parser/parseSelectEndOptions1'],
            ['parser/parseSelectEndOptions2'],
            ['parser/parseSelectEndOptionsErr'],
            ['parser/parseSelectUnion'],
            ['parser/parseSelectUnion2'],
            ['parser/parseSelectWhere'],
            ['parser/parseSelectWhereCollate'],
            ['parser/parseSelectIndexHint1'],
            ['parser/parseSelectIndexHint2'],
            ['parser/parseSelectOrderByIsNull'],
            ['parser/parseSelectIndexHintErr1'],
            ['parser/parseSelectIndexHintErr2'],
            ['parser/parseSelectIndexHintErr3'],
            ['parser/parseSelectIndexHintErr4'],
            ['parser/parseSelectWithParenthesis'],
            ['parser/parseSelectOrderByComment'],
            ['parser/parseSelectOverAlias_mariadb_100600'],
            ['parser/parseSelectGroupBy'],
            ['parser/parseSelectGroupByErr'],
            ['parser/parseSelectGroupByWithComments'],
            ['parser/parseTable1'],
        ];
    }
}
