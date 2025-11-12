#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSinglePlanBlock();
timeout=0
cid=0

- 测试步骤1：测试正常显示产品计划数据，包含5个计划属性plansCount @5
- 测试步骤2：测试显示产品计划数据时显示计划数量限制为3属性plansCount @3
- 测试步骤3：测试产品没有计划时的情况属性plansCount @0
- 测试步骤4：测试计划数量限制为10时显示所有计划属性plansCount @5
- 测试步骤5：测试计划数量限制为0时应该显示所有计划属性plansCount @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->status->range('normal');
$product->type->range('normal');
$product->deleted->range('0');
$product->gen(5);

$productplan = zenData('productplan');
$productplan->id->range('1-10');
$productplan->product->range('1{5},2{5}');
$productplan->branch->range('0');
$productplan->title->range('计划1,计划2,计划3,计划4,计划5,计划6,计划7,计划8,计划9,计划10');
$productplan->status->range('wait{3},doing{4},done{3}');
$productplan->begin->range('`2024-01-01`,`2024-02-01`,`2024-03-01`,`2024-04-01`,`2024-05-01`')->type('timestamp')->format('YYYY-MM-DD');
$productplan->end->range('`2024-02-01`,`2024-03-01`,`2024-04-01`,`2024-05-01`,`2024-06-01`')->type('timestamp')->format('YYYY-MM-DD');
$productplan->deleted->range('0');
$productplan->gen(10);

$blockTest = new blockZenTest();

$block1 = new stdClass();
$block1->params = new stdClass();
$block1->params->count = 5;

$block2 = new stdClass();
$block2->params = new stdClass();
$block2->params->count = 3;

$block3 = new stdClass();
$block3->params = new stdClass();
$block3->params->count = 5;

$block4 = new stdClass();
$block4->params = new stdClass();
$block4->params->count = 10;

$block5 = new stdClass();
$block5->params = new stdClass();
$block5->params->count = 0;

r($blockTest->printSinglePlanBlockTest($block1, 1)) && p('plansCount') && e('5'); // 测试步骤1：测试正常显示产品计划数据，包含5个计划
r($blockTest->printSinglePlanBlockTest($block2, 1)) && p('plansCount') && e('3'); // 测试步骤2：测试显示产品计划数据时显示计划数量限制为3
r($blockTest->printSinglePlanBlockTest($block3, 3)) && p('plansCount') && e('0'); // 测试步骤3：测试产品没有计划时的情况
r($blockTest->printSinglePlanBlockTest($block4, 1)) && p('plansCount') && e('5'); // 测试步骤4：测试计划数量限制为10时显示所有计划
r($blockTest->printSinglePlanBlockTest($block5, 1)) && p('plansCount') && e('5'); // 测试步骤5：测试计划数量限制为0时应该显示所有计划