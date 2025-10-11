#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processTaskRecord();
timeout=0
cid=0

- 执行processTaskRecordTest($record1)模块的url, 'm=task') !== false方法  @1
- 执行processTaskRecordTest($record2)模块的url, 'f=view') !== false方法  @1
- 执行processTaskRecordTest($record3)模块的url, 'id=3') !== false方法  @1
- 执行searchTest模块的processTaskRecordTest方法，参数是$record4 属性dataApp @project
- 执行searchTest模块的processTaskRecordTest方法，参数是$record5 属性dataApp @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10');
$task->project->range('1,2,3,4,5,1,2,3,4,5');
$task->execution->range('1-10');
$task->type->range('task{10}');
$task->status->range('wait{3},doing{4},done{3}');
$task->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project{5}');
$project->multiple->range('1,1,0,0,0');
$project->gen(5);

su('admin');

$searchTest = new searchTest();

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
$record4->objectID = 3;

$record5 = new stdClass();
$record5->objectType = 'task';
$record5->objectID = 1;

r(strpos($searchTest->processTaskRecordTest($record1)->url, 'm=task') !== false) && p() && e('1');
r(strpos($searchTest->processTaskRecordTest($record2)->url, 'f=view') !== false) && p() && e('1');
r(strpos($searchTest->processTaskRecordTest($record3)->url, 'id=3') !== false) && p() && e('1');
r($searchTest->processTaskRecordTest($record4)) && p('dataApp') && e('project');
r($searchTest->processTaskRecordTest($record5)) && p('dataApp') && e('~~');