#!/usr/bin/env php
<?php
/**

title=productpanModel->updateParentStatus();
timout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

$plan = zdTable('productplan')->config('productplan');
$plan->parent->range('0,`-1`,2,`-1`,4,`-1`,6,`-1`,8');
$plan->status->range('doing,doing,wait,doing,closed,doing,done,wait,doing');
$plan->gen(9);

$parentIdList = array(1, 2, 4, 6, 8);

$planTester = new productPlan('admin');
r($planTester->updateParentStatusTest($parentIdList[0])) && p('0:field,old,new') && e('status,doing,wait');   // 测试将计划1的状态更新为wait
r($planTester->updateParentStatusTest($parentIdList[1])) && p()                  && e('0');                   // 测试不更新计划2的状态
r($planTester->updateParentStatusTest($parentIdList[2])) && p('0:field,old,new') && e('status,doing,closed'); // 测试将计划4的状态更新为closed
r($planTester->updateParentStatusTest($parentIdList[3])) && p('0:field,old,new') && e('status,doing,done');   // 测试将计划4的状态更新为done
r($planTester->updateParentStatusTest($parentIdList[4])) && p('0:field,old,new') && e('status,wait,doing');   // 测试将计划4的状态更新为doing
