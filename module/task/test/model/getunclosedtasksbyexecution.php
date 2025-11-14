#!/usr/bin/env php
<?php

/**

title=taskModel->getUnclosedTasksByExecution();
cid=18824

- 查询单个执行 101 未关闭的任务 @开发任务11
- 查询单个执行 102 未关闭的任务 @开发任务12
- 查询单个执行 106 未关闭的任务 @0
- 查询 执行 101 未关闭的任务id和执行id @1,101

- 查询 执行 102 未关闭的任务id和执行id @2,102

- 查询 执行 106 未关闭的任务id和执行id @0
- 查询 执行 101 102 未关闭的任务id和执行id @1,101;2,102

- 查询 执行 101 106 未关闭的任务id和执行id @1,101

- 查询 执行 110 未关闭的任务id和执行id @0
- 查询 执行 110 未关闭的任务id和执行id @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('task')->gen(6);
zenData('project')->loadYaml('execution')->gen(10);

$excutionIdList = array(101, 102, 106, array(101), array(102), array(106), array(101, 102), array(101, 106), 110, array(110));

$task = new taskTest();
r($task->getUnclosedTasksByExecutionTest($excutionIdList[0])) && p() && e('开发任务11');  // 查询单个执行 101 未关闭的任务
r($task->getUnclosedTasksByExecutionTest($excutionIdList[1])) && p() && e('开发任务12');  // 查询单个执行 102 未关闭的任务
r($task->getUnclosedTasksByExecutionTest($excutionIdList[2])) && p() && e('0');           // 查询单个执行 106 未关闭的任务
r($task->getUnclosedTasksByExecutionTest($excutionIdList[3])) && p() && e('1,101');       // 查询 执行 101 未关闭的任务id和执行id
r($task->getUnclosedTasksByExecutionTest($excutionIdList[4])) && p() && e('2,102');       // 查询 执行 102 未关闭的任务id和执行id
r($task->getUnclosedTasksByExecutionTest($excutionIdList[5])) && p() && e('0');           // 查询 执行 106 未关闭的任务id和执行id
r($task->getUnclosedTasksByExecutionTest($excutionIdList[6])) && p() && e('1,101;2,102'); // 查询 执行 101 102 未关闭的任务id和执行id
r($task->getUnclosedTasksByExecutionTest($excutionIdList[7])) && p() && e('1,101');       // 查询 执行 101 106 未关闭的任务id和执行id
r($task->getUnclosedTasksByExecutionTest($excutionIdList[8])) && p() && e('0');           // 查询 执行 110 未关闭的任务id和执行id
r($task->getUnclosedTasksByExecutionTest($excutionIdList[9])) && p() && e('0');           // 查询 执行 110 未关闭的任务id和执行id
