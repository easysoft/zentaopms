#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->updateTeam();
timeout=0
cid=1

- 分配2个成员团队后任务
 - 第1条的field属性 @estimate
 - 第1条的old属性 @0
 - 第1条的new属性 @3.5

- 分配1个成员团队后任务
 - 第1条的field属性 @mode
 - 第1条的old属性 @multi
 - 第1条的new属性 @~~

- 分配预计剩余为0工时团队后任务 @总计消耗"和"预计剩余"不能同时为0

*/

function initData()
{
    $task = zdTable('task');
    $task->mode->range('multi');
    $task->gen(6);

    $user = zdTable('user')->gen(50);
}

initData();
$taskIDList     = array('1','2','3','4','5','6');
$taskStatusList = array('doing','wait','done');
$teamList       = array('user1','user2','user3');

$task = new taskTest();
r($task->updateTeamTest($taskIDList[0], $taskStatusList[1], array($teamList[0], $teamList[1]), array($teamList[0], $teamList[1]), $teamEstimateList = array(1, 2.5), $teamConsumedList = array(0, 0), $teamLeftList = array(1, 0.5))) && p('1:field,old,new') && e('estimate,0,3.5'); //分配2个成员团队后任务
r($task->updateTeamTest($taskIDList[1], $taskStatusList[1], array($teamList[0]), array($teamList[0]), $teamEstimateList = array(1), $teamConsumedList = array(0), $teamLeftList = array(1))) && p('1:field,old,new') && e('mode,multi,~~'); //分配1个成员团队后任务
r($task->updateTeamTest($taskIDList[2], $taskStatusList[1], array($teamList[0], $teamList[1]), array($teamList[0], $teamList[1]), $teamEstimateList = array(1, 2.5), $teamConsumedList = array(0, 0), $teamLeftList = array(0, 0))) && p() && e('"总计消耗"和"预计剩余"不能同时为0'); //分配预计剩余为0工时团队后任务
