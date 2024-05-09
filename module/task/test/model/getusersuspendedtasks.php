#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('task')->loadYaml('task')->gen(9);
zenData('project')->loadYaml('project')->gen(7);

/**

title=taskModel->getUserSuspendedTasks();
timeout=0
cid=1

*/

$accountList = array('', 'admin', 'user1', 'user2', 'user3');

$taskModel = $tester->loadModel('task');
r($taskModel->getUserSuspendedTasks($accountList[0]))        && p()               && e('0');     // 测试传入空值
r($taskModel->getUserSuspendedTasks($accountList[1]))        && p('4:assignedTo') && e('admin'); // 测试获取指派给admin的任务信息
r(count($taskModel->getUserSuspendedTasks($accountList[1]))) && p()               && e('2');     // 测试获取指派给admin的任务数量
r($taskModel->getUserSuspendedTasks($accountList[2]))        && p('2:assignedTo') && e('user1'); // 测试获取指派给user1的任务信息
r(count($taskModel->getUserSuspendedTasks($accountList[2]))) && p()               && e('3');     // 测试获取指派给user1的任务数量
r($taskModel->getUserSuspendedTasks($accountList[3]))        && p('3:assignedTo') && e('user2'); // 测试获取指派给user2的任务信息
r(count($taskModel->getUserSuspendedTasks($accountList[3]))) && p()               && e('3');     // 测试获取指派给user2的任务数量
r($taskModel->getUserSuspendedTasks($accountList[4]))        && p()               && e('0');     // 测试获取指派给不存在用户user3的任务信息
r(count($taskModel->getUserSuspendedTasks($accountList[4]))) && p()               && e('0');     // 测试获取指派给不存在用户user3的任务数量
