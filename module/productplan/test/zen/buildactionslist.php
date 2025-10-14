#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildActionsList();
timeout=0
cid=0

- 执行productplanTest模块的buildActionsListTest方法，参数是$plan1  @11
- 执行productplanTest模块的buildActionsListTest方法，参数是$emptyPlan  @11
- 执行productplanTest模块的buildActionsListTest方法，参数是$plan2  @11
- 执行productplanTest模块的buildActionsListTest方法，参数是$plan3 属性5 @divider
- 执行productplanTest模块的buildActionsListTest方法，参数是$plan1 属性10 @delete

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

zenData('productplan')->loadYaml('zt_productplan_buildactionslist', false, 2)->gen(10);

su('admin');

$productplanTest = new productplanZenTest();

$plan1 = new stdClass();
$plan1->id = 1;
$plan1->product = 1;
$plan1->title = '测试计划1';
$plan1->status = 'wait';

$plan2 = new stdClass();
$plan2->id = 2;
$plan2->product = 1;
$plan2->title = '测试计划2';
$plan2->status = 'doing';

$plan3 = new stdClass();
$plan3->id = 3;
$plan3->product = 1;
$plan3->title = '测试计划3';
$plan3->status = 'done';

$emptyPlan = new stdClass();

r(count($productplanTest->buildActionsListTest($plan1))) && p() && e('11');
r(count($productplanTest->buildActionsListTest($emptyPlan))) && p() && e('11');
r(count($productplanTest->buildActionsListTest($plan2))) && p() && e('11');
r($productplanTest->buildActionsListTest($plan3)) && p('5') && e('divider');
r($productplanTest->buildActionsListTest($plan1)) && p('10') && e('delete');