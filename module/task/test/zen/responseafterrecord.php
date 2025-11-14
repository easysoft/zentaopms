#!/usr/bin/env php
<?php

/**

title=测试 taskZen::responseAfterRecord();
timeout=0
cid=18947

- 执行taskTest模块的responseAfterRecordTest方法，参数是$task1, $changes1, '', false 属性result @success
- 执行taskTest模块的responseAfterRecordTest方法，参数是$task2, $changes2, '', false 属性result @success
- 执行taskTest模块的responseAfterRecordTest方法，参数是$task3, $changes3, '', false 属性result @success
- 执行taskTest模块的responseAfterRecordTest方法，参数是$task4, $changes4, '', false 属性result @success
- 执行taskTest模块的responseAfterRecordTest方法，参数是$task5, $changes5, '', true 属性result @success
- 执行taskTest模块的responseAfterRecordTest方法，参数是$task6, $changes5, '', true 属性result @success
- 执行taskTest模块的responseAfterRecordTest方法，参数是$task7, $changes5, 'taskkanban', true 属性callback @refreshKanban()

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1');
$task->execution->range('1{5},2{5}');
$task->fromBug->range('0,1,2,3,0,0,0,0,0,0');
$task->status->range('doing{5},done{5}');
$task->gen(10);

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->status->range('active{5},resolved{5}');
$bug->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0,1,1,0,4');
$project->name->range('项目1,执行1,执行2,项目2,执行3');
$project->type->range('project,sprint,sprint,project,sprint');
$project->gen(5);

su('admin');

$taskTest = new taskZenTest();

$task1 = new stdclass();
$task1->id = 1;
$task1->execution = 1;
$task1->fromBug = 0;
$task1->status = 'doing';

$task2 = new stdclass();
$task2->id = 2;
$task2->execution = 1;
$task2->fromBug = 1;
$task2->status = 'doing';

$task3 = new stdclass();
$task3->id = 3;
$task3->execution = 1;
$task3->fromBug = 2;
$task3->status = 'done';

$task4 = new stdclass();
$task4->id = 4;
$task4->execution = 1;
$task4->fromBug = 6;
$task4->status = 'done';

$task5 = new stdclass();
$task5->id = 5;
$task5->execution = 1;
$task5->fromBug = 0;
$task5->status = 'done';

$task6 = new stdclass();
$task6->id = 6;
$task6->execution = 2;
$task6->fromBug = 0;
$task6->status = 'done';

$task7 = new stdclass();
$task7->id = 7;
$task7->execution = 2;
$task7->fromBug = 0;
$task7->status = 'done';

$changes1 = array();
$changes2 = array();
$changes3 = array(array('field' => 'status', 'old' => 'doing', 'new' => 'done'));
$changes4 = array(array('field' => 'status', 'old' => 'doing', 'new' => 'done'));
$changes5 = array(array('field' => 'status', 'old' => 'doing', 'new' => 'done'));

r($taskTest->responseAfterRecordTest($task1, $changes1, '', false)) && p('result') && e('success');
r($taskTest->responseAfterRecordTest($task2, $changes2, '', false)) && p('result') && e('success');
r($taskTest->responseAfterRecordTest($task3, $changes3, '', false)) && p('result') && e('success');
r($taskTest->responseAfterRecordTest($task4, $changes4, '', false)) && p('result') && e('success');
r($taskTest->responseAfterRecordTest($task5, $changes5, '', true)) && p('result') && e('success');
r($taskTest->responseAfterRecordTest($task6, $changes5, '', true)) && p('result') && e('success');
r($taskTest->responseAfterRecordTest($task7, $changes5, 'taskkanban', true)) && p('callback') && e('refreshKanban()');