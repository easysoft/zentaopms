#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::getSummary();
timeout=0
cid=0

- 执行productplanTest模块的getSummaryTest方法，参数是$planList1  @本页共 <strong>0</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>0</strong>，独立计划 <strong>0</strong>。
- 执行productplanTest模块的getSummaryTest方法，参数是$planList2  @本页共 <strong>3</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>0</strong>，独立计划 <strong>3</strong>。
- 执行productplanTest模块的getSummaryTest方法，参数是$planList3  @本页共 <strong>2</strong> 个计划，父计划 <strong>2</strong>，子计划 <strong>0</strong>，独立计划 <strong>0</strong>。
- 执行productplanTest模块的getSummaryTest方法，参数是$planList4  @本页共 <strong>2</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>2</strong>，独立计划 <strong>0</strong>。
- 执行productplanTest模块的getSummaryTest方法，参数是$planList5  @本页共 <strong>10</strong> 个计划，父计划 <strong>2</strong>，子计划 <strong>5</strong>，独立计划 <strong>3</strong>。
- 执行productplanTest模块的getSummaryTest方法，参数是$planList6  @本页共 <strong>1</strong> 个计划，父计划 <strong>1</strong>，子计划 <strong>0</strong>，独立计划 <strong>0</strong>。
- 执行productplanTest模块的getSummaryTest方法，参数是$planList7  @本页共 <strong>4</strong> 个计划，父计划 <strong>1</strong>，子计划 <strong>3</strong>，独立计划 <strong>0</strong>。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productplanTest = new productplanZenTest();

// 准备测试数据
// 测试场景1: 空数组
$planList1 = array();

// 测试场景2: 只包含独立计划(parent=0)
$planList2 = array();
$plan1 = new stdClass();
$plan1->id = 1;
$plan1->parent = 0;
$planList2[] = $plan1;

$plan2 = new stdClass();
$plan2->id = 2;
$plan2->parent = 0;
$planList2[] = $plan2;

$plan3 = new stdClass();
$plan3->id = 3;
$plan3->parent = 0;
$planList2[] = $plan3;

// 测试场景3: 只包含父计划(parent=-1)
$planList3 = array();
$parentPlan1 = new stdClass();
$parentPlan1->id = 10;
$parentPlan1->parent = -1;
$planList3[] = $parentPlan1;

$parentPlan2 = new stdClass();
$parentPlan2->id = 11;
$parentPlan2->parent = -1;
$planList3[] = $parentPlan2;

// 测试场景4: 只包含子计划(parent>0)
$planList4 = array();
$childPlan1 = new stdClass();
$childPlan1->id = 20;
$childPlan1->parent = 10;
$planList4[] = $childPlan1;

$childPlan2 = new stdClass();
$childPlan2->id = 21;
$childPlan2->parent = 10;
$planList4[] = $childPlan2;

// 测试场景5: 混合类型
$planList5 = array();
// 父计划
$parentPlanA = new stdClass();
$parentPlanA->id = 100;
$parentPlanA->parent = -1;
$planList5[] = $parentPlanA;

$parentPlanB = new stdClass();
$parentPlanB->id = 101;
$parentPlanB->parent = -1;
$planList5[] = $parentPlanB;

// 子计划
$childPlanA1 = new stdClass();
$childPlanA1->id = 200;
$childPlanA1->parent = 100;
$planList5[] = $childPlanA1;

$childPlanA2 = new stdClass();
$childPlanA2->id = 201;
$childPlanA2->parent = 100;
$planList5[] = $childPlanA2;

$childPlanB1 = new stdClass();
$childPlanB1->id = 202;
$childPlanB1->parent = 101;
$planList5[] = $childPlanB1;

$childPlanB2 = new stdClass();
$childPlanB2->id = 203;
$childPlanB2->parent = 101;
$planList5[] = $childPlanB2;

$childPlanB3 = new stdClass();
$childPlanB3->id = 204;
$childPlanB3->parent = 101;
$planList5[] = $childPlanB3;

// 独立计划
$independentPlan1 = new stdClass();
$independentPlan1->id = 300;
$independentPlan1->parent = 0;
$planList5[] = $independentPlan1;

$independentPlan2 = new stdClass();
$independentPlan2->id = 301;
$independentPlan2->parent = 0;
$planList5[] = $independentPlan2;

$independentPlan3 = new stdClass();
$independentPlan3->id = 302;
$independentPlan3->parent = 0;
$planList5[] = $independentPlan3;

// 测试场景6: 单个父计划
$planList6 = array();
$singleParent = new stdClass();
$singleParent->id = 400;
$singleParent->parent = -1;
$planList6[] = $singleParent;

// 测试场景7: 父计划和子计划组合
$planList7 = array();
$parentPlanC = new stdClass();
$parentPlanC->id = 500;
$parentPlanC->parent = -1;
$planList7[] = $parentPlanC;

$childPlanC1 = new stdClass();
$childPlanC1->id = 501;
$childPlanC1->parent = 500;
$planList7[] = $childPlanC1;

$childPlanC2 = new stdClass();
$childPlanC2->id = 502;
$childPlanC2->parent = 500;
$planList7[] = $childPlanC2;

$childPlanC3 = new stdClass();
$childPlanC3->id = 503;
$childPlanC3->parent = 500;
$planList7[] = $childPlanC3;

r($productplanTest->getSummaryTest($planList1)) && p() && e('本页共 <strong>0</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>0</strong>，独立计划 <strong>0</strong>。');
r($productplanTest->getSummaryTest($planList2)) && p() && e('本页共 <strong>3</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>0</strong>，独立计划 <strong>3</strong>。');
r($productplanTest->getSummaryTest($planList3)) && p() && e('本页共 <strong>2</strong> 个计划，父计划 <strong>2</strong>，子计划 <strong>0</strong>，独立计划 <strong>0</strong>。');
r($productplanTest->getSummaryTest($planList4)) && p() && e('本页共 <strong>2</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>2</strong>，独立计划 <strong>0</strong>。');
r($productplanTest->getSummaryTest($planList5)) && p() && e('本页共 <strong>10</strong> 个计划，父计划 <strong>2</strong>，子计划 <strong>5</strong>，独立计划 <strong>3</strong>。');
r($productplanTest->getSummaryTest($planList6)) && p() && e('本页共 <strong>1</strong> 个计划，父计划 <strong>1</strong>，子计划 <strong>0</strong>，独立计划 <strong>0</strong>。');
r($productplanTest->getSummaryTest($planList7)) && p() && e('本页共 <strong>4</strong> 个计划，父计划 <strong>1</strong>，子计划 <strong>3</strong>，独立计划 <strong>0</strong>。');