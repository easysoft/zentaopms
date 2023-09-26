#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

/**

title=productpanModel->batchUpdate();
timeout=0
cid=1

*/

zdTable('user')->gen(5);
zdTable('productplan')->config('productplan')->gen(5);
zdTable('product')->config('product')->gen(5);

$productID = 1;

$plans = array();
$plans[1] = new stdClass();
$plans[1]->title = '修改计划名称1';
$plans[1]->begin = '2021-01-01';
$plans[1]->end   = '2021-06-30';

$plans[2] = clone $plans[1];
$plans[2]->title = '修改计划名称2';
$plans[2]->begin = '2021-06-01';
$plans[2]->end   = '2021-06-15';

$plans[3] = clone $plans[1];
$plans[3]->title = '修改计划名称3';
$plans[3]->begin = '2022-01-01';
$plans[3]->end   = '2022-02-28';

$planTester = new productPlan('admin');
$changes = $planTester->batchUpdateTest($productID, $plans);
r($changes[1]) && p('0:field,old,new') && e('title,计划1,修改计划名称1'); // 测试修改父计划的名称
r($changes[2]) && p('0:field,old,new') && e('title,计划2,修改计划名称2'); // 测试修改子计划的名称
r($changes[3]) && p('0:field,old,new') && e('title,计划3,修改计划名称3'); // 测试修改普通计划的名称
r($changes[3]) && p('1:field,old,new') && e('end,2022-01-30,2022-02-28'); // 测试修改普通计划的结束时间
