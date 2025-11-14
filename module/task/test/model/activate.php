#!/usr/bin/env php
<?php

/**

title=taskModel->activate();
timeout=0
cid=18755

- wait状态任务激活属性status @doing
- doing状态任务激活属性status @doing
- done状态任务激活属性status @doing
- cancel状态任务激活属性status @doing
- closed状态任务激活属性status @doing
- wait状态串行任务激活属性status @doing
- doing状态并行任务激活属性status @doing
- wait状态任务激活属性status @doing
- doing状态任务激活属性status @doing
- done状态任务激活属性status @doing
- cancel状态任务激活属性status @doing
- closed状态任务激活属性status @doing
- wait状态串行任务激活属性status @doing
- doing状态并行任务激活属性status @doing

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('effort')->gen(0);
zenData('project')->loadYaml('project')->gen(6);
zenData('task')->loadYaml('task')->gen(9);
zenData('taskteam')->loadYaml('taskteam')->gen(6);
zenData('kanbanregion')->loadYaml('kanbanregion')->gen(1);
zenData('kanbanlane')->loadYaml('kanbanlane')->gen(1);
zenData('kanbancolumn')->loadYaml('kanbancolumn')->gen(7);
zenData('kanbancell')->loadYaml('kanbancell')->gen(7);

$accountList      = array('admin', 'user1', 'user2', 'user3');
$teamEstimateList = array(1, 2, 3, 4);
$teamConsumedList = array(4, 3, 2, 1);
$teamLeftList     = array(1, 1, 1, 1);
$teamSourceList   = array('admin', 'user1', 'user2', 'user3');

$teamData = new stdclass();
$teamData->team         = $accountList;
$teamData->teamLeft     = $teamLeftList;
$teamData->teamSource   = $teamSourceList;
$teamData->teamEstimate = $teamEstimateList;
$teamData->teamConsumed = $teamConsumedList;

$emptyTeamData = new stdclass();

$drag = array('fromColID' => 1, 'toColID' => 2, 'fromLaneID' => 1, 'toLaneID' => 1);

$taskIDList = range(1, 9);

$task = new taskTest();
r($task->activateTest($taskIDList[0], '', $emptyTeamData)) && p('status') && e('doing'); // wait状态任务激活
r($task->activateTest($taskIDList[1], '', $emptyTeamData)) && p('status') && e('doing'); // doing状态任务激活
r($task->activateTest($taskIDList[2], '', $emptyTeamData)) && p('status') && e('doing'); // done状态任务激活
r($task->activateTest($taskIDList[3], '', $emptyTeamData)) && p('status') && e('doing'); // cancel状态任务激活
r($task->activateTest($taskIDList[4], '', $emptyTeamData)) && p('status') && e('doing'); // closed状态任务激活
r($task->activateTest($taskIDList[7], '', $teamData))      && p('status') && e('doing'); // wait状态串行任务激活
r($task->activateTest($taskIDList[8], '', $teamData))      && p('status') && e('doing'); // doing状态并行任务激活

zenData('task')->loadYaml('task')->gen(9);
zenData('taskteam')->loadYaml('taskteam')->gen(6);

r($task->activateTest($taskIDList[0], '', $emptyTeamData, $drag)) && p('status') && e('doing'); // wait状态任务激活
r($task->activateTest($taskIDList[1], '', $emptyTeamData, $drag)) && p('status') && e('doing'); // doing状态任务激活
r($task->activateTest($taskIDList[2], '', $emptyTeamData, $drag)) && p('status') && e('doing'); // done状态任务激活
r($task->activateTest($taskIDList[3], '', $emptyTeamData, $drag)) && p('status') && e('doing'); // cancel状态任务激活
r($task->activateTest($taskIDList[4], '', $emptyTeamData, $drag)) && p('status') && e('doing'); // closed状态任务激活
r($task->activateTest($taskIDList[7], '', $teamData,      $drag)) && p('status') && e('doing'); // wait状态串行任务激活
r($task->activateTest($taskIDList[8], '', $teamData,      $drag)) && p('status') && e('doing'); // doing状态并行任务激活
