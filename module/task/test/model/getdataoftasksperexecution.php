#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerExecution();
timeout=0
cid=18799

- 统计executionID为2的执行的任务数量
 - 第2条的name属性 @迭代2
 - 第2条的value属性 @1
- 统计executionID为3的执行的任务数量
 - 第3条的name属性 @迭代3
 - 第3条的value属性 @2
- 统计executionID为5的执行的任务数量
 - 第5条的name属性 @0
 - 第5条的value属性 @0

*/

$project = zenData('project');
$project->id->range('2-5');
$project->project->range('6-9');
$project->name->prefix("迭代")->range('2-5');
$project->code->prefix("project")->range('2-5');
$project->auth->range("[]");
$project->path->range("`,6,2,`,`,7,3,`,`,8,4,`,`,9,5,`");
$project->type->range("sprint");
$project->grade->range("1");
$project->days->range("1");
$project->status->range("wait");
$project->desc->range("[]");
$project->budget->range("100000,200000");
$project->budgetUnit->range("CNY");
$project->percent->range("0-0");
$project->gen(4);

$task = zenData('task');
$task->id->range('2-5');
$task->execution->range('2,3,3,4');
$task->name->prefix("任务")->range('1-5');
$task->status->range("wait");
$task->gen(4);

$tester->session->set('taskOnlyCondition', true);

$task = new taskTest();
r($task->getDataOfTasksPerExecutionTest(2)) && p('2:name,value') && e('迭代2,1'); // 统计executionID为2的执行的任务数量
r($task->getDataOfTasksPerExecutionTest(3)) && p('3:name,value') && e('迭代3,2'); // 统计executionID为3的执行的任务数量
r($task->getDataOfTasksPerExecutionTest(5)) && p('5:name,value') && e('0,0');     // 统计executionID为5的执行的任务数量