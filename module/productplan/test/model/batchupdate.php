#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

/**

title=productpanModel->batchUpdate();
timeout=0
cid=17620

- 测试修改父计划的名称
 - 第0条的field属性 @title
 - 第0条的old属性 @计划1
 - 第0条的new属性 @修改计划名称1
- 测试修改子计划的名称
 - 第0条的field属性 @title
 - 第0条的old属性 @计划2
 - 第0条的new属性 @修改计划名称2
- 测试修改普通计划的名称
 - 第0条的field属性 @title
 - 第0条的old属性 @计划3
 - 第0条的new属性 @修改计划名称3
- 测试修改普通计划的结束时间
 - 第1条的field属性 @end
 - 第1条的old属性 @2022-01-30
 - 第1条的new属性 @2022-02-28
- 测试修改普通计划的状态
 - 第2条的field属性 @status
 - 第2条的old属性 @done
 - 第2条的new属性 @doing

*/

zenData('user')->gen(5);
zenData('productplan')->loadYaml('productplan')->gen(5);
zenData('product')->loadYaml('product')->gen(5);

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
$plans[3]->title  = '修改计划名称3';
$plans[3]->begin  = '2022-01-01';
$plans[3]->end    = '2022-02-28';
$plans[3]->status = 'doing';

$planTester = new productPlan('admin');
$changes = $planTester->batchUpdateTest($productID, $plans);
r($changes[1]) && p('0:field,old,new') && e('title,计划1,修改计划名称1'); // 测试修改父计划的名称
r($changes[2]) && p('0:field,old,new') && e('title,计划2,修改计划名称2'); // 测试修改子计划的名称
r($changes[3]) && p('0:field,old,new') && e('title,计划3,修改计划名称3'); // 测试修改普通计划的名称
r($changes[3]) && p('1:field,old,new') && e('end,2022-01-30,2022-02-28'); // 测试修改普通计划的结束时间
r($changes[3]) && p('2:field,old,new') && e('status,done,doing');         // 测试修改普通计划的状态
