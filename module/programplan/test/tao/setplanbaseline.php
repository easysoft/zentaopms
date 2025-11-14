#!/usr/bin/env php
<?php

/**

title=测试 programplanTao::setPlanBaseline()
timeout=0
cid=17779

- 执行programplan模块的setPlanBaseline方法，参数是$oldPlans, $newPlans 
 - 第1条的name属性 @Plan A
 - 第1条的version属性 @1
 - 第1条的milestone属性 @1
 - 第1条的begin属性 @2023-01-01
 - 第1条的end属性 @2023-01-31
- 执行programplan模块的setPlanBaseline方法，参数是$oldPlans, $newPlans2 第2条的name属性 @Plan B
- 执行programplan模块的setPlanBaseline方法，参数是$oldPlans, array  @0
- 执行programplan模块的setPlanBaseline方法，参数是array 第1条的name属性 @New Plan A
- 执行programplan模块的setPlanBaseline方法，参数是$oldPlans2, $newPlans4 第1条的version属性 @2

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('programplan');

$oldPlans[1] = new stdclass();
$oldPlans[1]->version = 1;
$oldPlans[1]->name = 'Plan A';
$oldPlans[1]->milestone = '1';
$oldPlans[1]->begin = '2023-01-01';
$oldPlans[1]->end = '2023-01-31';

$newPlans[1] = new stdclass();
$newPlans[1]->id = 1;
$newPlans[1]->currentName = 'Current Plan';

$newPlans2[2] = new stdclass();
$newPlans2[2]->name = 'Plan B';

$newPlans3[1] = new stdclass();
$newPlans3[1]->name = 'New Plan A';

$oldPlans2[1] = new stdclass();
$oldPlans2[1]->version = 2;
$oldPlans2[1]->name = '';
$oldPlans2[1]->milestone = '0';
$oldPlans2[1]->begin = '';
$oldPlans2[1]->end = '';

$newPlans4[1] = new stdclass();
$newPlans4[1]->currentName = 'Current Plan';

r($tester->programplan->setPlanBaseline($oldPlans, $newPlans))   && p('1:name,version,milestone,begin,end')   && e('Plan A,1,1,2023-01-01,2023-01-31');
r($tester->programplan->setPlanBaseline($oldPlans, $newPlans2)) && p('2:name') && e('Plan B');
r($tester->programplan->setPlanBaseline($oldPlans, array()))    && p() && e('0');
r($tester->programplan->setPlanBaseline(array(), $newPlans3))   && p('1:name') && e('New Plan A');
r($tester->programplan->setPlanBaseline($oldPlans2, $newPlans4)) && p('1:version') && e('2');