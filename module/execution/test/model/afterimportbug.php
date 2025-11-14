#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3,4,5');
$task->status->range('wait,doing');
$task->estimate->range('6');
$task->left->range('3');
$task->consumed->range('3');
$task->gen(10);

$bug = zenData('bug');
$bug->id->range('1-5');
$bug->title->range('1-5')->prefix('Bug');
$bug->project->range('1,2,1');
$bug->execution->range('3,4,5');
$bug->task->range('1-10');
$bug->status->range('active');
$bug->gen(5);

su('admin');

/**

title=测试executionModel->afterImportBug();
timeout=0
cid=16260

- 测试导入Bug1后的数据处理 @1
- 测试导入Bug2后的数据处理 @1
- 测试导入Bug3后的数据处理 @1
- 测试导入Bug4后的数据处理 @1
- 测试导入Bug5后的数据处理 @1

*/

$taskIdList = array(1, 2, 3, 4, 5);
$bugIdList  = array(1, 2, 3, 4, 5);

$executionTester = new executionTest();
r($executionTester->afterImportBugTest($taskIdList[0], $bugIdList[0])) && p() && e('1'); // 测试导入Bug1后的数据处理
r($executionTester->afterImportBugTest($taskIdList[1], $bugIdList[1])) && p() && e('1'); // 测试导入Bug2后的数据处理
r($executionTester->afterImportBugTest($taskIdList[2], $bugIdList[2])) && p() && e('1'); // 测试导入Bug3后的数据处理
r($executionTester->afterImportBugTest($taskIdList[3], $bugIdList[3])) && p() && e('1'); // 测试导入Bug4后的数据处理
r($executionTester->afterImportBugTest($taskIdList[4], $bugIdList[4])) && p() && e('1'); // 测试导入Bug5后的数据处理
