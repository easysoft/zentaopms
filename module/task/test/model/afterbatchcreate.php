#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('project')->config('project')->gen('5');
zdTable('task')->config('task')->gen(10);
zdTable('taskspec')->gen(0);

/**

title=测试taskModel->afterBatchCreate();
timeout=0
cid=1

*/

$taskIdList = array(array(1, 2), array(3, 4), array(5, 6));

$taskTester = new taskTest();

r($taskTester->afterBatchCreateObject($taskIdList[0])) && p() && e('1'); // 测试批量创建迭代下的任务后的数据处理
r($taskTester->afterBatchCreateObject($taskIdList[1])) && p() && e('1'); // 测试批量创建阶段下的任务后的数据处理
r($taskTester->afterBatchCreateObject($taskIdList[2])) && p() && e('1'); // 测试批量创建看板下的任务后的数据处理

r($taskTester->afterBatchCreateObject($taskIdList[0], 1)) && p() && e('1'); // 测试批量创建迭代下的子任务后的数据处理
r($taskTester->afterBatchCreateObject($taskIdList[1], 1)) && p() && e('1'); // 测试批量创建阶段下的子任务后的数据处理
r($taskTester->afterBatchCreateObject($taskIdList[2], 1)) && p() && e('1'); // 测试批量创建看板下的子任务后的数据处理
