#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('execution')->gen(30);
$team = zenData('team')->loadYaml('team');
$team->account->range('admin');
$team->gen(30);

$task = zenData('task')->loadYaml('task');
$task->project->range('11,60,100');
$task->execution->range('101,106,124');
$task->assignedTo->range('admin');
$task->gen(30);

su('admin');

/**

title=测试executionModel->importTaskTest();
timeout=0
cid=16350

- 敏捷执行导入任务
 - 第0条的id属性 @1
 - 第0条的execution属性 @101
- 瀑布执行导入任务
 - 第0条的id属性 @2
 - 第0条的execution属性 @106
- 看板执行导入任务
 - 第0条的id属性 @9
 - 第0条的execution属性 @124
- 敏捷执行导入任务统计 @9
- 瀑布执行导入任务统计 @13
- 看板执行导入任务统计 @8

*/

$executionIDList  = array(101, 106, 124);
$sprintTasks      = array(1, 3, 5, 10, 13, 19, 22, 28);
$stageTasks       = array(2, 4, 6, 7, 8, 11, 14, 16);
$kanbanTasks      = array(9, 12, 18);
$count            = array(0, 1);

$execution = new executionModelTest();
r($execution->importTaskTest($executionIDList[0], $count[0], $sprintTasks)) && p('0:id,execution') && e('1,101'); // 敏捷执行导入任务
r($execution->importTaskTest($executionIDList[1], $count[0], $stageTasks))  && p('0:id,execution') && e('2,106'); // 瀑布执行导入任务
r($execution->importTaskTest($executionIDList[2], $count[0], $kanbanTasks)) && p('0:id,execution') && e('9,124'); // 看板执行导入任务
r($execution->importTaskTest($executionIDList[0], $count[1], $sprintTasks)) && p()                 && e('9');     // 敏捷执行导入任务统计
r($execution->importTaskTest($executionIDList[1], $count[1], $stageTasks))  && p()                 && e('13');    // 瀑布执行导入任务统计
r($execution->importTaskTest($executionIDList[2], $count[1], $kanbanTasks)) && p()                 && e('8');     // 看板执行导入任务统计