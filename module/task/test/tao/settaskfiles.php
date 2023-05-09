#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/task.class.php';

$task = zdTable('task');
$task->id->range('1-6');
$task->name->prefix('任务')->range('1-6');
$task->gen(6);

$file = zdTable('file');
$file->id->range('1-10');
$file->title->prefix('附件')->range('1-10');
$file->objectType->range('task');
$file->objectID->range('1-5');
$file->gen(10);

/**

title=taskModel->setTaskFiles();
timeout=0
cid=1

*/

$taskIdList = range(1, 5);

$taskTester  = new taskTest();
$emptyData   = $taskTester->setTaskFilesTest(array(), 0);
$taskFiles   = $taskTester->setTaskFilesTest(array(), 5);
$emptyTaskID = $taskTester->setTaskFilesTest($taskIdList, 0);
$insertData  = $taskTester->setTaskFilesTest($taskIdList, 6);

r($emptyData)         && p()             && e('0');  // 测试空数据
r($emptyTaskID)       && p()             && e('0');  // 测试传入的任务ID
r(count($insertData)) && p()             && e('10'); // 测试插入任务附件的数量
r($insertData)        && p('1:objectID') && e('6');  // 测试插入附件数据的对象ID
r(count($taskFiles))  && p()             && e('0');  // 测试获取任务ID为5的附件数据的数量
