#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project')->gen('5');
zenData('task')->loadYaml('task')->gen(10);
zenData('taskspec')->gen(0);

/**

title=测试taskModel->afterBatchCreate();
timeout=0
cid=18758

- 测试批量创建迭代下的任务后的数据处理 @1
- 测试批量创建阶段下的任务后的数据处理 @1
- 测试批量创建看板下的任务后的数据处理 @1
- 测试批量创建迭代下的子任务后的数据处理 @1
- 测试批量创建阶段下的子任务后的数据处理 @1
- 测试批量创建看板下的子任务后的数据处理 @1

*/

global $tester;
$taskIdList = array(array(1, 2), array(3, 4), array(5, 6));
$task = $tester->loadModel('task')->fetchByID(1);

$taskTester = new taskTest();

r($taskTester->afterBatchCreateObject($taskIdList[0])) && p() && e('1'); // 测试批量创建迭代下的任务后的数据处理
r($taskTester->afterBatchCreateObject($taskIdList[1])) && p() && e('1'); // 测试批量创建阶段下的任务后的数据处理
r($taskTester->afterBatchCreateObject($taskIdList[2])) && p() && e('1'); // 测试批量创建看板下的任务后的数据处理

r($taskTester->afterBatchCreateObject($taskIdList[0], $task)) && p() && e('1'); // 测试批量创建迭代下的子任务后的数据处理
r($taskTester->afterBatchCreateObject($taskIdList[1], $task)) && p() && e('1'); // 测试批量创建阶段下的子任务后的数据处理
r($taskTester->afterBatchCreateObject($taskIdList[2], $task)) && p() && e('1'); // 测试批量创建看板下的子任务后的数据处理
