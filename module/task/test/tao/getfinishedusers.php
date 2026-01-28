#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('user')->gen(5);
zenData('task')->loadYaml('task', true)->gen(9);

$taskteam = zenData('taskteam');
$taskteam->id->range('1-6');
$taskteam->task->range('8{3},9{3}');
$taskteam->account->range('admin,user1,user2');
$taskteam->status->range('wait{2},done{2},wait{2}');
$taskteam->gen(6);

su('admin');

/**

title=taskModel->getFinishedUsers();
timeout=0
cid=18878

- 测试普通任务获取多人任务的完成者 @0
- 测试父任务获取多人任务的完成者 @0
- 测试子任务获取多人任务的完成者 @0
- 测试串行任务获取多人任务的完成者属性3 @user2
- 测试并行任务获取多人任务的完成者属性4 @admin
- 测试普通任务获取指定人员多人任务的完成者 @0
- 测试父任务获取指定人员多人任务的完成者 @0
- 测试子任务获取指定人员多人任务的完成者 @0
- 测试串行任务指定人员获取多人任务的完成者属性3 @user2
- 测试并行任务指定人员获取多人任务的完成者属性4 @admin
- 测试普通任务获取指定不存在人员多人任务的完成者 @0
- 测试父任务获取指定不存在人员多人任务的完成者 @0
- 测试子任务获取指定不存在人员多人任务的完成者 @0
- 测试串行任务指定不存在人员获取多人任务的完成者 @0
- 测试并行任务指定不存在人员获取多人任务的完成者 @0

*/

$taskIdList    = array(1, 6, 7, 8, 9);
$memberList[0] = array('admin', 'user1', 'user2');
$memberList[1] = array('ceshi1', 'ceshi2');

global $tester;
$taskModule = $tester->loadModel('task');

r($taskModule->getFinishedUsers($taskIdList[0])) && p()    && e('0');     // 测试普通任务获取多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[1])) && p()    && e('0');     // 测试父任务获取多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[2])) && p()    && e('0');     // 测试子任务获取多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[3])) && p('3') && e('user2'); // 测试串行任务获取多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[4])) && p('4') && e('admin'); // 测试并行任务获取多人任务的完成者

r($taskModule->getFinishedUsers($taskIdList[0], $memberList[0])) && p()    && e('0');     // 测试普通任务获取指定人员多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[1], $memberList[0])) && p()    && e('0');     // 测试父任务获取指定人员多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[2], $memberList[0])) && p()    && e('0');     // 测试子任务获取指定人员多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[3], $memberList[0])) && p('3') && e('user2'); // 测试串行任务指定人员获取多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[4], $memberList[0])) && p('4') && e('admin'); // 测试并行任务指定人员获取多人任务的完成者

r($taskModule->getFinishedUsers($taskIdList[0], $memberList[1])) && p() && e('0'); // 测试普通任务获取指定不存在人员多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[1], $memberList[1])) && p() && e('0'); // 测试父任务获取指定不存在人员多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[2], $memberList[1])) && p() && e('0'); // 测试子任务获取指定不存在人员多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[3], $memberList[1])) && p() && e('0'); // 测试串行任务指定不存在人员获取多人任务的完成者
r($taskModule->getFinishedUsers($taskIdList[4], $memberList[1])) && p() && e('0'); // 测试并行任务指定不存在人员获取多人任务的完成者