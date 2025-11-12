#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getValidObjects();
timeout=0
cid=0

- 执行$result @3
- 执行$result['product'] @5
- 执行$result['project'] @8
- 执行$result['execution'] @6
- 执行$result['product'][6] @0
- 执行$result['project'][9] @0
- 执行$result['execution'][7] @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->program->range('0');
$product->name->range('测试产品{10}');
$product->code->range('prod{10}');
$product->shadow->range('0');
$product->status->range('normal{5},closed{5}');
$product->deleted->range('0{8},1{2}');
$product->closedDate->range('`0000-00-00`{5},`2025-01-01`{3},`2020-01-01`{2}');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-27');
$project->project->range('0');
$project->type->range('project{15},sprint{6},stage{3},kanban{3}');
$project->name->range('测试项目{27}');
$project->code->range('proj{27}');
$project->status->range('doing{8},closed{4},done{3},doing{6},closed{3},done{3}');
$project->deleted->range('0{12},1{3},0{10},1{2}');
$project->closedDate->range('`0000-00-00`{8},`2025-01-01`{4},`2020-01-01`{3},`0000-00-00`{6},`2025-01-01`{3},`2020-01-01`{3}');
$project->gen(27);

su('admin');

$metricTest = new metricZenTest();

$result = $metricTest->getValidObjectsZenTest();

r(count($result)) && p() && e('3');
r(count($result['product'])) && p() && e('5');
r(count($result['project'])) && p() && e('8');
r(count($result['execution'])) && p() && e('6');
r(isset($result['product'][6])) && p() && e('0');
r(isset($result['project'][9])) && p() && e('0');
r(isset($result['execution'][7])) && p() && e('0');