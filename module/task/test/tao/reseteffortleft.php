#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('task')->loadYaml('task')->gen(9);
zenData('effort')->loadYaml('effort')->gen(3);

/**

title=taskModel->resetEffortLeft();
timeout=0
cid=18889

*/

$taskIdList = array(0, 1, 5);
$members    = array('', 'guest', 'admin');

$task = new taskTest();

r($task->resetEffortLeftTest($taskIdList[0], $members[0])) && p() && e('0');  // 测试任务ID为空，用户名为空的情况
r($task->resetEffortLeftTest($taskIdList[0], $members[1])) && p() && e('0');  // 测试任务ID为空，用户名不存在的情况
r($task->resetEffortLeftTest($taskIdList[0], $members[2])) && p() && e('0');  // 测试任务ID为空，用户名正确的情况

r($task->resetEffortLeftTest($taskIdList[1], $members[0])) && p()          && e('0');    // 测试任务ID正确，用户名为空的情况
r($task->resetEffortLeftTest($taskIdList[1], $members[1])) && p()          && e('0');    // 测试任务ID正确，用户名不存在的情况
r($task->resetEffortLeftTest($taskIdList[1], $members[2])) && p('id,left') && e('1,0');  // 测试任务ID正确，用户名正确的情况

r($task->resetEffortLeftTest($taskIdList[2], $members[0])) && p() && e('0');  // 测试任务ID错误，用户名为空的情况
r($task->resetEffortLeftTest($taskIdList[2], $members[1])) && p() && e('0');  // 测试任务ID错误，用户名不存在的情况
r($task->resetEffortLeftTest($taskIdList[2], $members[2])) && p() && e('0');  // 测试任务ID错误，用户名正确的情况
