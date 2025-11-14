#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');
zenData('effort')->loadYaml('effort')->gen(5);

/**

title=taskModel->getTaskEfforts();
cid=18820

- 任务ID为空的情况 @0
- 任务ID正确的情况
 - 第1条的id属性 @1
 - 第1条的objectType属性 @task
- 任务ID错误的情况 @0
- 任务ID正确，用户账号为空的情况第1条的account属性 @admin
- 任务ID正确，用户账号正确的情况第1条的objectType属性 @task
- 任务ID正确，用户账号错误的情况 @0
- 任务ID正确，用户账号正确的情况，日志ID为空的情况第1条的account属性 @admin
- 任务ID正确，用户账号正确的情况，日志ID正确的情况第1条的objectType属性 @task
- 任务ID正确，用户账号正确的情况，日志ID错误的情况第1条的work属性 @这是工作内容1

*/

$userList     = array('', 'admin', 'guest');
$taskIdList   = array(0, 1, 10);
$effortIdList = array(0, 1, 10);

$task = new taskTest();
r($task->getTaskEffortsTest($taskIdList[0])) && p()                  && e('0');      // 任务ID为空的情况
r($task->getTaskEffortsTest($taskIdList[1])) && p('1:id,objectType') && e('1,task'); // 任务ID正确的情况
r($task->getTaskEffortsTest($taskIdList[2])) && p()                  && e('0');      // 任务ID错误的情况

r($task->getTaskEffortsTest($taskIdList[1], $userList[0])) && p('1:account')    && e('admin'); // 任务ID正确，用户账号为空的情况
r($task->getTaskEffortsTest($taskIdList[1], $userList[1])) && p('1:objectType') && e('task');  // 任务ID正确，用户账号正确的情况
r($task->getTaskEffortsTest($taskIdList[1], $userList[2])) && p()               && e('0');     // 任务ID正确，用户账号错误的情况

r($task->getTaskEffortsTest($taskIdList[1], $userList[1], $effortIdList[0])) && p('1:account')    && e('admin');         // 任务ID正确，用户账号正确的情况，日志ID为空的情况
r($task->getTaskEffortsTest($taskIdList[1], $userList[1], $effortIdList[1])) && p('1:objectType') && e('task');          // 任务ID正确，用户账号正确的情况，日志ID正确的情况
r($task->getTaskEffortsTest($taskIdList[1], $userList[1], $effortIdList[2])) && p('1:work')       && e('这是工作内容1'); // 任务ID正确，用户账号正确的情况，日志ID错误的情况
