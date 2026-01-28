#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('project')->gen(5);
zenData('task')->loadYaml('task')->gen(8);
zenData('kanbancolumn')->loadYaml('kanbancolumn')->gen(10);
zenData('kanbancell')->loadYaml('kanbancell')->gen(10);

/**

title=taskModel->batchcreate();
timeout=0
cid=18854


*/

$taskIdList      = array(1, 2, 3, 4, 5, 6, 7, 8);
$executionIdList = array('2', '3', '4', '5');
$laneIdList      = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
$columnIdList    = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');

$task = new taskModelTest();

r($task->updateKanbanForBatchCreateTest($taskIdList[1], $executionIdList[2], $laneIdList[1], $columnIdList[1]))         && p() && e('0');   // 综合研发界面下看板项目的任务泳道未开始列无卡片的情况。
r($task->updateKanbanForBatchCreateTest($taskIdList[1], $executionIdList[2], $laneIdList[2], $columnIdList[2]))         && p() && e('0');   // 综合研发界面下看板项目的任务泳道未开始列有卡片的情况。
r($task->updateKanbanForBatchCreateTest($taskIdList[2], $executionIdList[2], $laneIdList[9], $columnIdList[2]))         && p() && e('0');   // 综合研发界面下看板项目任务泳道为空未开始列的情况。
r($task->updateKanbanForBatchCreateTest($taskIdList[2], $executionIdList[2], $laneIdList[9], $columnIdList[2]))         && p() && e('0');   // 综合研发界面下看板项目任务泳道列为空的情况。
r($task->updateKanbanForBatchCreateTest($taskIdList[5], $executionIdList[3], $laneIdList[1], $columnIdList[2], 'lite')) && p() && e(',6,'); // 运营管理界面下看板项目任务泳道未开始列无卡片的情况。
r($task->updateKanbanForBatchCreateTest($taskIdList[5], $executionIdList[3], $laneIdList[3], $columnIdList[3], 'lite')) && p() && e(',6,'); // 运营管理界面下看板项目任务泳道未开始列有卡片的情况。
