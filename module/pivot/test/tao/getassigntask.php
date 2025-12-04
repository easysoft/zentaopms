#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getassigntask();
timeout=0
cid=0

- 执行$tasks @45
- 执行$tasks[0]
 - 属性id @1
 - 属性user @admin
 - 属性executionID @11
 - 属性projectID @1
- 执行$tasks @4
- 执行$tasks @4
- 执行$tasks @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$project = zenData('project');
$project->project->range('0{10},1{10}');
$project->gen(20);
$task = zenData('task');
$task->project->range('1');
$task->execution->range('11-20');
$task->assignedTo->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9,user10');
$task->gen(100);

su('admin');

global $tester;
$pivotModel = $tester->loadModel('pivot');

$tasks = $pivotModel->getassigntask(array());
r(count($tasks)) && p() && e('45');
r($tasks[0]) && p('id,user,executionID,projectID') && e('1,admin,11,1');

$tasks = $pivotModel->getassigntask(array('admin'));
r(count($tasks)) && p() && e('4');

$tasks = $pivotModel->getassigntask(array('user1'));
r(count($tasks)) && p() && e('4');

$tasks = $pivotModel->getassigntask(array('admin', 'user1'));
r(count($tasks)) && p() && e('8');