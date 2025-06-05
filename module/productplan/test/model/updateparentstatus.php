#!/usr/bin/env php
<?php
/**

title=productpanModel->updateParentStatus();
timout=0
cid=1

- 测试将计划1的状态更新为wait
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @wait
- 测试不更新计划2的状态 @0
- 测试将计划4的状态更新为closed
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @closed
- 测试将计划4的状态更新为done
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @done
- 测试将计划4的状态更新为doing
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @doing

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

$plan = zenData('productplan')->loadYaml('productplan');
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
