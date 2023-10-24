<?php

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class SelectStatementTest extends TestCase
{
    public function testSelectOptions()
    {
        $data = $this->getData('parser/parseSelect');
        $parser = new Parser($data['query']);
        $stmt = $parser->statements[0];
        $this->assertEquals(10, $stmt->options->has('MAX_STATEMENT_TIME'));
    }

    /**
     * @dataProvider selectProvider
     *
     * @param mixed $test
     */
    public function testSelect($test)
    {
        $this->runParserTest($test);
    }

    public function selectProvider()
    {
        return array(
            array('parser/parseSelect2'),
            array('parser/parseSelect3'),
            array('parser/parseSelect4'),
            array('parser/parseSelect5'),
            array('parser/parseSelect6'),
            array('parser/parseSelect7'),
            array('parser/parseSelect8'),
            array('parser/parseSelect9'),
            array('parser/parseSelect10'),
            array('parser/parseSelect11'),
            array('parser/parseSelectErr1'),
            array('parser/parseSelectErr2'),
            array('parser/parseSelectNested'),
            array('parser/parseSelectCase1'),
            array('parser/parseSelectCase2'),
            array('parser/parseSelectCase3'),
            array('parser/parseSelectCase4'),
            array('parser/parseSelectCase5'),
            array('parser/parseSelectCaseErr1'),
            array('parser/parseSelectCaseErr2'),
            array('parser/parseSelectCaseErr3'),
            array('parser/parseSelectCaseErr4'),
            array('parser/parseSelectCaseErr5'),
            array('parser/parseSelectCaseAlias1'),
            array('parser/parseSelectCaseAlias2'),
            array('parser/parseSelectCaseAlias3'),
            array('parser/parseSelectCaseAlias4'),
            array('parser/parseSelectCaseAlias5'),
            array('parser/parseSelectCaseAlias6'),
            array('parser/parseSelectCaseAliasErr1'),
            array('parser/parseSelectCaseAliasErr2'),
            array('parser/parseSelectCaseAliasErr3'),
            array('parser/parseSelectCaseAliasErr4'),
            array('parser/parseSelectExists'),
            array('parser/parseSelectIntoOptions1'),
            array('parser/parseSelectIntoOptions2'),
            array('parser/parseSelectIntoOptions3'),
            array('parser/parseSelectJoinCross'),
            array('parser/parseSelectJoinNatural'),
            array('parser/parseSelectJoinNaturalLeft'),
            array('parser/parseSelectJoinNaturalRight'),
            array('parser/parseSelectJoinNaturalLeftOuter'),
            array('parser/parseSelectJoinNaturalRightOuter'),
            array('parser/parseSelectJoinMultiple'),
            array('parser/parseSelectJoinMultiple2'),
            array('parser/parseSelectWrongOrder'),
            array('parser/parseSelectWrongOrder2'),
            array('parser/parseSelectEndOptions1'),
            array('parser/parseSelectEndOptions2'),
            array('parser/parseSelectEndOptionsErr'),
            array('parser/parseSelectUnion'),
            array('parser/parseSelectUnion2'),
            array('parser/parseSelectIndexHint1'),
            array('parser/parseSelectIndexHint2'),
            array('parser/parseSelectIndexHintErr1'),
            array('parser/parseSelectIndexHintErr2'),
            array('parser/parseSelectIndexHintErr3'),
            array('parser/parseSelectIndexHintErr4'),
            array('parser/parseSelectWithParenthesis'),
            array('parser/parseSelectOrderByComment')
        );
    }
}
