#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->importTaskTest();
cid=1
pid=1

敏捷执行导入任务 >> 91,641
瀑布执行导入任务 >> 32,672
敏捷执行导入任务统计 >> 5
瀑布执行导入任务统计 >> 5

*/

$executionIDList  = array('641','672');
$sprintTaskIDlist = array('181', '91', '871', '872');
$stageTaskIDlist  = array('122', '32', '694', '695');
$sprintTasks      = array('tasks' => $sprintTaskIDlist);
$stageTasks       = array('tasks' => $stageTaskIDlist);
$count            = array('0','1');

$execution = new executionTest();
r($execution->importTaskTest($executionIDList[0], $count[0], $sprintTasks)) && p('0:id,execution') && e('91,641');  // 敏捷执行导入任务
r($execution->importTaskTest($executionIDList[1], $count[0], $stageTasks))  && p('0:id,execution') && e('32,672');  // 瀑布执行导入任务
r($execution->importTaskTest($executionIDList[0], $count[1], $sprintTasks)) && p()                 && e('5');       // 敏捷执行导入任务统计
r($execution->importTaskTest($executionIDList[1], $count[1], $stageTasks))  && p()                 && e('5');       // 瀑布执行导入任务统计

$db->restoreDB();