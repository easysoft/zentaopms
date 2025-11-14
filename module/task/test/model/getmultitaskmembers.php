#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('task')->loadYaml('task', true)->gen(10);
zenData('taskteam')->loadYaml('taskteam', true)->gen(6);

/**

title=taskModel->getMultiTaskMembers();
timeout=0
cid=18815

- 测试传入普通任务 @0
- 测试传入父任务 @0
- 测试传入子任务 @0
- 测试传入串行任务 @0
- 测试传入并行任务 @admin

*/

$taskIdList = array(1, 6, 7, 8, 9);

global $tester;
$taskModule = $tester->loadModel('task');

r($taskModule->getMultiTaskMembers($taskIdList[0])) && p()    && e('0');     // 测试传入普通任务
r($taskModule->getMultiTaskMembers($taskIdList[1])) && p()    && e('0');     // 测试传入父任务
r($taskModule->getMultiTaskMembers($taskIdList[2])) && p()    && e('0');     // 测试传入子任务
r($taskModule->getMultiTaskMembers($taskIdList[3])) && p()    && e('0');     // 测试传入串行任务
r($taskModule->getMultiTaskMembers($taskIdList[4])) && p('0') && e('admin'); // 测试传入并行任务