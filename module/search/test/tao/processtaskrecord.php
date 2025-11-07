#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1-10');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10');
$task->status->range('wait');
$task->deleted->range('0');
$task->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project');
$project->multiple->range('1,0,1,0,1,0,1,0,1,0');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(10);

su('admin');

/**

title=测试 searchTao::processTaskRecord();
timeout=0
cid=0

- 执行searchTest模块的processTaskRecordTest方法，参数是$record1 属性url @task-view-1
- 执行searchTest模块的processTaskRecordTest方法，参数是$record2
 - 属性url @task-view-2
 - 属性dataApp @project
- 执行searchTest模块的processTaskRecordTest方法，参数是$record3 属性url @task-view-3
- 执行searchTest模块的processTaskRecordTest方法，参数是$record4
 - 属性url @task-view-4
 - 属性dataApp @project
- 执行searchTest模块的processTaskRecordTest方法，参数是$record5 属性url @task-view-5
- 执行searchTest模块的processTaskRecordTest方法，参数是$record6
 - 属性url @task-view-6
 - 属性dataApp @project
- 执行searchTest模块的processTaskRecordTest方法，参数是$record7 属性url @task-view-7

*/

$searchTest = new searchTaoTest();

$record1 = new stdClass();
$record1->objectType = 'task';
$record1->objectID = 1;

$record2 = new stdClass();
$record2->objectType = 'task';
$record2->objectID = 2;

$record3 = new stdClass();
$record3->objectType = 'task';
$record3->objectID = 3;

$record4 = new stdClass();
$record4->objectType = 'task';
$record4->objectID = 4;

$record5 = new stdClass();
$record5->objectType = 'task';
$record5->objectID = 5;

$record6 = new stdClass();
$record6->objectType = 'task';
$record6->objectID = 6;

$record7 = new stdClass();
$record7->objectType = 'task';
$record7->objectID = 7;

r($searchTest->processTaskRecordTest($record1)) && p('url') && e('task-view-1');
r($searchTest->processTaskRecordTest($record2)) && p('url;dataApp') && e('task-view-2;project');
r($searchTest->processTaskRecordTest($record3)) && p('url') && e('task-view-3');
r($searchTest->processTaskRecordTest($record4)) && p('url;dataApp') && e('task-view-4;project');
r($searchTest->processTaskRecordTest($record5)) && p('url') && e('task-view-5');
r($searchTest->processTaskRecordTest($record6)) && p('url;dataApp') && e('task-view-6;project');
r($searchTest->processTaskRecordTest($record7)) && p('url') && e('task-view-7');