#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=taskModel->updateTeam();
timeout=0
cid=18859

- 分配2个成员团队后任务
 - 第0条的field属性 @estimate
 - 第0条的old属性 @0
 - 第0条的new属性 @3.5
- 分配1个成员团队后任 @团队成员必须大于1人
- 分配预计剩余为0工时团队后任务 @"总计消耗"和"预计剩余"不能同时为0
- 改变团队第二个成员后任务团队属性1 @user3
- 增加一个成员后任务团队属性2 @user2

*/

$task = zenData('task');
$task->mode->range('multi');
$task->gen(6);

zenData('team')->gen(0);
zenData('taskteam')->gen(0);
zenData('effort')->gen(0);

$user = zenData('user')->gen(50);

$taskIDList     = array(1,2,3,4,5,6);
$taskStatusList = array('doing','wait','done');
$teamList       = array('user1','user2','user3');

$task = new taskTest();
r($task->updateTeamTest($taskIDList[0], $taskStatusList[1], array($teamList[0], $teamList[1]), array($teamList[0], $teamList[1]), $teamEstimateList = array(1, 2.5), $teamConsumedList = array(0, 0), $teamLeftList = array(1, 0.5)))                              && p('0:field,old,new') && e('estimate,0,3.5');                    //分配2个成员团队后任务
r($task->updateTeamTest($taskIDList[1], $taskStatusList[1], array($teamList[0]), array($teamList[0]), $teamEstimateList = array(1), $teamConsumedList = array(0), $teamLeftList = array(1)))                                                                       && p()                  && e('团队成员必须大于1人');               //分配1个成员团队后任
r($task->updateTeamTest($taskIDList[2], $taskStatusList[1], array($teamList[0], $teamList[1]), array($teamList[0], $teamList[1]), $teamEstimateList = array(1, 2.5), $teamConsumedList = array(0, 0), $teamLeftList = array(0, 0)))                                && p()                  && e('"总计消耗"和"预计剩余"不能同时为0'); //分配预计剩余为0工时团队后任务
r($task->updateTeamTest($taskIDList[0], $taskStatusList[0], array($teamList[0], $teamList[2]), array($teamList[0], $teamList[1]), $teamEstimateList = array(1, 2.5), $teamConsumedList = array(0, 0), $teamLeftList = array(1, 0.5), true))                        && p(1)                 && e('user3');                             //改变团队第二个成员后任务团队
r($task->updateTeamTest($taskIDList[0], $taskStatusList[0], array($teamList[0], $teamList[2], $teamList[1]), array($teamList[0], $teamList[2]), $teamEstimateList = array(1, 2.5, 3), $teamConsumedList = array(0, 0, 0), $teamLeftList = array(1, 0.5, 3), true)) && p(2)                 && e('user2');                             //增加一个成员后任务团队