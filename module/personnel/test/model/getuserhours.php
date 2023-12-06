#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

$project = zdTable('project');
$project->id->range('1-6');
$project->project->range('0{3},3,4,3');
$project->type->range('program,project{2},sprint{8}');
$project->status->range('doing{3},wait{7}');
$project->vision->range('rnd');
$project->name->range('1-10')->prefix('Object');
$project->multiple->range('1');
$project->deleted->range('`0`');
$project->gen(6);

$task = zdTable('task');
$task->id->range('1-20');
$task->name->range('task1');
$task->project->range('2{2},3{2}');
$task->execution->range('4{2},5{2}');
$task->type->range('dev');
$task->left->range('1');
$task->openedBy->range('admin,user1');
$task->consumed->range('5');
$task->deleted->range('`0`');
$task->gen(20);

$effort = zdTable('effort');
$effort->id->range('1-30');
$effort->objectType->range('task');
$effort->objectID->range('1-20');
$effort->account->range('admin,user1');
$effort->left->range('7,1,3');
$effort->consumed->range('2,3');
$effort->deleted->range('`0`');
$effort->gen(30);

zdTable('user')->gen(20);

/**

title=测试 personnelModel->getUserHours();
cid=1
pid=1

*/

$personnel = new personnelTest('admin');

$projectIdList = array(2, 3, 100);
$accounts  = array(array('admin' => 'admin', 'user1' => 'user1'), array('user2' => 'user2'));

$result1 = $personnel->getUserHoursTest($projectIdList[0], $accounts[0]);
$result2 = $personnel->getUserHoursTest($projectIdList[1], $accounts[0]);
$result3 = $personnel->getUserHoursTest($projectIdList[2], $accounts[0]);
$result4 = $personnel->getUserHoursTest($projectIdList[0], $accounts[1]);
$result5 = $personnel->getUserHoursTest($projectIdList[1], $accounts[1]);
$result6 = $personnel->getUserHoursTest($projectIdList[2], $accounts[1]);

r($result1['admin']) && p('left,consumed') && e('19,16'); // 测试获取 项目 2 下用户 admin 的工时
r($result1['user1']) && p('left,consumed') && e('15,24'); // 测试获取 项目 2 下用户 user1 的工时
r($result2['admin']) && p('left,consumed') && e('15,14'); // 测试获取 项目 3 下用户 admin 的工时
r($result2['user1']) && p('left,consumed') && e('21,21'); // 测试获取 项目 3 下用户 user1 的工时
r($result3)          && p()                && e('0');     // 测试获取 不存在的 项目 100 下用户 admin 的工时
r($result4)          && p()                && e('0');     // 测试获取 项目 2 下不存在的用户 user2 的工时
r($result5)          && p()                && e('0');     // 测试获取 项目 3 下不存在的用户 user2 的工时
r($result6)          && p()                && e('0');     // 测试获取 不存在的 项目 100 下不存在的用户 user2 的工时
