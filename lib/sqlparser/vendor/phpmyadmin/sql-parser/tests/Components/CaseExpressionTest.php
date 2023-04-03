<?php

namespace PhpMyAdmin\SqlParser\Tests\Components;

use PhpMyAdmin\SqlParser\Components\CaseExpression;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Tests\TestCase;

class CaseExpressionTest extends TestCase
{
    public function testParseBuild()
    {
        $caseExprQuery = 'case 1 when 1 then "Some" else "Other" end';
        $component = CaseExpression::parse(
            new Parser(),
            $this->getTokensList($caseExprQuery)
        );
        $this->assertEquals(
            CaseExpression::build($component),
            'CASE 1 WHEN 1 THEN "Some" ELSE "Other" END'
        );
    }

    public function testParseBuild2()
    {
        $caseExprQuery = 'case when 1=1 then "India" else "Other" end';
        $component = CaseExpression::parse(
            new Parser(),
            $this->getTokensList($caseExprQuery)
        );
        $this->assertEquals(
            CaseExpression::build($component),
            'CASE WHEN 1=1 THEN "India" ELSE "Other" END'
        );
    }

    public function testParseBuild3()
    {
        $caseExprQuery = 'case 1 when 1 then "Some" '
            . 'when 2 then "SomeOther" else "Other" end';
        $component = CaseExpression::parse(
            new Parser(),
            $this->getTokensList($caseExprQuery)
        );
        $this->assertEquals(
            CaseExpression::build($component),
            'CASE 1 WHEN 1 THEN "Some" WHEN 2 THEN "SomeOther" ELSE "Other" END'
        );
    }

    public function testParseBuild4()
    {
        $caseExprQuery = 'case 1 when 1 then "Some" '
            . 'when 2 then "SomeOther" end';
        $component = CaseExpression::parse(
            new Parser(),
            $this->getTokensList($caseExprQuery)
        );
        $this->assertEquals(
            CaseExpression::build($component),
            'CASE 1 WHEN 1 THEN "Some" WHEN 2 THEN "SomeOther" END'
        );
    }

    public function testParseBuild5()
    {
        $caseExprQuery = 'case when 1=1 then "Some" '
            . 'when 1=2 then "SomeOther" else "Other" end';
        $component = CaseExpression::parse(
            new Parser(),
            $this->getTokensList($caseExprQuery)
        );
        $this->assertEquals(
            CaseExpression::build($component),
            'CASE WHEN 1=1 THEN "Some" WHEN 1=2 THEN "SomeOther" ELSE "Other" END'
        );
    }

    public function testParseBuild6()
    {
        $caseExprQuery = 'case when 1=1 then "Some" '
            . 'when 1=2 then "SomeOther" end';
        $component = CaseExpression::parse(
            new Parser(),
            $this->getTokensList($caseExprQuery)
        );
        $this->assertEquals(
            CaseExpression::build($component),
            'CASE WHEN 1=1 THEN "Some" WHEN 1=2 THEN "SomeOther" END'
        );
    }

    public function testParseBuild7()
    {
        $caseExprQuery = 'case when 1=1 then "Some" '
            . 'when 1=2 then "SomeOther" end AS foo';
        $component = CaseExpression::parse(
            new Parser(),
            $this->getTokensList($caseExprQuery)
        );
        $this->assertEquals(
            CaseExpression::build($component),
            'CASE WHEN 1=1 THEN "Some" WHEN 1=2 THEN "SomeOther" END AS `foo`'
        );
    }

    public function testParseBuild8()
    {
        $caseExprQuery = 'case when 1=1 then "Some" '
            . 'when 1=2 then "SomeOther" end foo';
        $component = CaseExpression::parse(
            new Parser(),
            $this->getTokensList($caseExprQuery)
        );
        $this->assertEquals(
            CaseExpression::build($component),
            'CASE WHEN 1=1 THEN "Some" WHEN 1=2 THEN "SomeOther" END AS `foo`'
        );
    }

    public function testBuildWithIncompleteCaseExpression()
    {
        $incomplete_case_expression_component = new CaseExpression();
        $this->assertEquals('CASE END', CaseExpression::build($incomplete_case_expression_component));
    }
}
