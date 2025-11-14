#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project', true)->gen(5);
zenData('task')->loadYaml('task', true)->gen(5);

/**

title=taskModel->addTaskEffort();
timeout=0
cid=18756

- 插入task为1 left为0 consumed为3的任务
 - 属性objectID @1
 - 属性left @0
 - 属性consumed @3
- 插入task为2 left为0 consumed为3的任务
 - 属性objectID @2
 - 属性left @0
 - 属性consumed @3
- 插入task为3 left为1 consumed为4的任务
 - 属性objectID @3
 - 属性left @1
 - 属性consumed @4
- 插入task为4 left为3 consumed为6的任务
 - 属性objectID @4
 - 属性left @3
 - 属性consumed @6
- 插入task为5 left为6 consumed为9的任务
 - 属性objectID @5
 - 属性left @6
 - 属性consumed @9

*/

$record1 = new stdclass();
$record1->account  = 'po82';
$record1->task     = 1;
$record1->left     = 0;
$record1->consumed = 3;

$record2 = new stdclass();
$record2->account  = 'po82';
$record2->task     = 2;
$record2->left     = 0;
$record2->consumed = 3;

$record3 = new stdclass();
$record3->account  = 'po82';
$record3->task     = 3;
$record3->left     = 1;
$record3->consumed = 4;

$record4 = new stdclass();
$record4->account  = 'po82';
$record4->task     = 4;
$record4->left     = 3;
$record4->consumed = 6;

$record5 = new stdclass();
$record5->account  = 'po82';
$record5->task     = 5;
$record5->left     = 6;
$record5->consumed = 9;

$task = new taskTest();
r($task->addTaskEffortTest($record1)) && p('objectID,left,consumed') && e("1,0,3"); // 插入task为1 left为0 consumed为3的任务
r($task->addTaskEffortTest($record2)) && p('objectID,left,consumed') && e("2,0,3"); // 插入task为2 left为0 consumed为3的任务
r($task->addTaskEffortTest($record3)) && p('objectID,left,consumed') && e("3,1,4"); // 插入task为3 left为1 consumed为4的任务
r($task->addTaskEffortTest($record4)) && p('objectID,left,consumed') && e("4,3,6"); // 插入task为4 left为3 consumed为6的任务
r($task->addTaskEffortTest($record5)) && p('objectID,left,consumed') && e("5,6,9"); // 插入task为5 left为6 consumed为9的任务
