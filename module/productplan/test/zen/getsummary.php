#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::getSummary();
timeout=0
cid=0

- 执行productplanZenTest模块的getSummaryTest方法，参数是$mixedPlans  @本页共 <strong>5</strong> 个计划，父计划 <strong>1</strong>，子计划 <strong>2</strong>，独立计划 <strong>2</strong>。
- 执行productplanZenTest模块的getSummaryTest方法，参数是$parentPlans  @本页共 <strong>1</strong> 个计划，父计划 <strong>1</strong>，子计划 <strong>0</strong>，独立计划 <strong>0</strong>。
- 执行productplanZenTest模块的getSummaryTest方法，参数是$childPlans  @本页共 <strong>2</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>2</strong>，独立计划 <strong>0</strong>。
- 执行productplanZenTest模块的getSummaryTest方法，参数是$independentPlans  @本页共 <strong>2</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>0</strong>，独立计划 <strong>2</strong>。
- 执行productplanZenTest模块的getSummaryTest方法，参数是$emptyPlans  @本页共 <strong>0</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>0</strong>，独立计划 <strong>0</strong>。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$productplanZenTest = new productplanZenTest();

$plan1 = new stdClass();
$plan1->id = 1;
$plan1->parent = -1;

$plan2 = new stdClass();
$plan2->id = 2;
$plan2->parent = 1;

$plan3 = new stdClass();
$plan3->id = 3;
$plan3->parent = 1;

$plan4 = new stdClass();
$plan4->id = 4;
$plan4->parent = 0;

$plan5 = new stdClass();
$plan5->id = 5;
$plan5->parent = 0;

$mixedPlans = array($plan1, $plan2, $plan3, $plan4, $plan5);
$parentPlans = array($plan1);
$childPlans = array($plan2, $plan3);
$independentPlans = array($plan4, $plan5);
$emptyPlans = array();

r($productplanZenTest->getSummaryTest($mixedPlans)) && p() && e('本页共 <strong>5</strong> 个计划，父计划 <strong>1</strong>，子计划 <strong>2</strong>，独立计划 <strong>2</strong>。');
r($productplanZenTest->getSummaryTest($parentPlans)) && p() && e('本页共 <strong>1</strong> 个计划，父计划 <strong>1</strong>，子计划 <strong>0</strong>，独立计划 <strong>0</strong>。');
r($productplanZenTest->getSummaryTest($childPlans)) && p() && e('本页共 <strong>2</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>2</strong>，独立计划 <strong>0</strong>。');
r($productplanZenTest->getSummaryTest($independentPlans)) && p() && e('本页共 <strong>2</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>0</strong>，独立计划 <strong>2</strong>。');
r($productplanZenTest->getSummaryTest($emptyPlans)) && p() && e('本页共 <strong>0</strong> 个计划，父计划 <strong>0</strong>，子计划 <strong>0</strong>，独立计划 <strong>0</strong>。');