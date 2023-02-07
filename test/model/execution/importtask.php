#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zdTable('task');
$task->id->range('1-10');
$task->execution->range('3');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

su('admin');

/**

title=测试executionModel->importTaskTest();
cid=1
pid=1

敏捷执行导入任务     >> 1,3
瀑布执行导入任务     >> 2,4
敏捷执行导入任务统计 >> 6
瀑布执行导入任务统计 >> 4

*/

$executionIDList  = array('3','4');
$sprintTaskIDlist = array('1', '3', '5', '7');
$stageTaskIDlist  = array('2', '4', '6', '8');
$sprintTasks      = array('tasks' => $sprintTaskIDlist);
$stageTasks       = array('tasks' => $stageTaskIDlist);
$count            = array('0','1');

$execution = new executionTest();
r($execution->importTaskTest($executionIDList[0], $count[0], $sprintTasks)) && p('0:id,execution') && e('1,3');  // 敏捷执行导入任务
r($execution->importTaskTest($executionIDList[1], $count[0], $stageTasks))  && p('0:id,execution') && e('2,4');  // 瀑布执行导入任务
r($execution->importTaskTest($executionIDList[0], $count[1], $sprintTasks)) && p()                 && e('6');       // 敏捷执行导入任务统计
r($execution->importTaskTest($executionIDList[1], $count[1], $stageTasks))  && p()                 && e('4');       // 瀑布执行导入任务统计
