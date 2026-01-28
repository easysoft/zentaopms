#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$task = zenData('task');
$task->id->range('1-6');
$task->name->prefix('任务')->range('1-6');
$task->gen(6);

$file = zenData('file');
$file->id->range('1-10');
$file->title->prefix('附件')->range('1-10');
$file->objectType->range('task');
$file->objectID->range('1-5');
$file->gen(10);

/**

title=taskModel->setTaskFiles();
timeout=0
cid=18843

- 测试空数据 @1
- 测试传入数组中只有一个任务ID的情况 @1
- 测试传入数组中有多个任务ID的情况 @1
- 测试传入int类型的taskID为5 @1
- 测试传入int类型的taskID为0 @1

*/

$taskIdList = range(1, 5);

$taskTester = new taskModelTest();
$emptyData         = $taskTester->setTaskFilesTest(array());
$multipleTaskData1 = $taskTester->setTaskFilesTest(array(5));
$multipleTaskData2 = $taskTester->setTaskFilesTest($taskIdList);
$singleTaskData1   = $taskTester->setTaskFilesTest(5);
$singleTaskData2   = $taskTester->setTaskFilesTest(0);

r($emptyData)         && p() && e('1');  // 测试空数据
r($multipleTaskData1) && p() && e('1');  // 测试传入数组中只有一个任务ID的情况
r($multipleTaskData2) && p() && e('1');  // 测试传入数组中有多个任务ID的情况
r($singleTaskData1)   && p() && e('1');  // 测试传入int类型的taskID为5
r($singleTaskData2)   && p() && e('1');  // 测试传入int类型的taskID为0
