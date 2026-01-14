#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$task = zenData('task');
$task->id->range('1-4');
$task->name->range('并行任务,串行任务1,串行任务2,普通任务');
$task->mode->range('multi,linear{2},[]');
$task->status->range('done,doing,done,wait');
$task->assignedTo->range('admin');
$task->openedBy->range('dev01');
$task->gen(4);

$taskTeam = zenData('taskteam');
$taskTeam->id->range('1-7');
$taskTeam->task->range('1{2},2{3},3{2}');
$taskTeam->account->range('admin,dev01,admin,dev01,dev02,admin,dev01');
$taskTeam->estimate->range('1{2},2{3},1{2}');
$taskTeam->left->range('1{2},1{3},1{2}');
$taskTeam->status->range('done{3},doing,wait,done{2}');
$taskTeam->gen(7);
su('admin');

/**

title=taskModel->getAssignedTo4Multi();
timeout=0
cid=18789

*/

$taskTester = new taskModelTest();

$taskIdList = range(1, 4);
r($taskTester->getAssignedTo4MultiTest($taskIdList[0]))         && p() && e('admin'); // 测试获取并行任务的指派人
r($taskTester->getAssignedTo4MultiTest($taskIdList[1]))         && p() && e('dev01'); // 测试获取串行任务的当前指派人
r($taskTester->getAssignedTo4MultiTest($taskIdList[1], 'next')) && p() && e('dev02'); // 测试获取并行任务的下一个指派人
r($taskTester->getAssignedTo4MultiTest($taskIdList[2]))         && p() && e('dev01'); // 测试获取已完成的串行任务的当前指派人
r($taskTester->getAssignedTo4MultiTest($taskIdList[3]))         && p() && e('admin'); // 测试获取普通任务的指派人
