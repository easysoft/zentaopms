#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

/**

title=taskModel->updateTeam();
timeout=0
cid=1

*/

$task = zdTable('task');
$task->mode->range('multi');
$task->gen(6);

$user = zdTable('user')->gen(50);

$taskIDList     = array(1,2,3,4,5,6);
$taskStatusList = array('doing','wait','done');
$teamList       = array('user1','user2','user3');

$task = new taskTest();
r($task->updateTeamTest($taskIDList[0], $taskStatusList[1], array($teamList[0], $teamList[1]), array($teamList[0], $teamList[1]), $teamEstimateList = array(1, 2.5), $teamConsumedList = array(0, 0), $teamLeftList = array(1, 0.5)))                              && p('0:field,old,new') && e('estimate,0,3.5');                    //分配2个成员团队后任务
r($task->updateTeamTest($taskIDList[1], $taskStatusList[1], array($teamList[0]), array($teamList[0]), $teamEstimateList = array(1), $teamConsumedList = array(0), $teamLeftList = array(1)))                                                                       && p()                  && e('团队成员必须大于1人');               //分配1个成员团队后任
r($task->updateTeamTest($taskIDList[2], $taskStatusList[1], array($teamList[0], $teamList[1]), array($teamList[0], $teamList[1]), $teamEstimateList = array(1, 2.5), $teamConsumedList = array(0, 0), $teamLeftList = array(0, 0)))                                && p()                  && e('"总计消耗"和"预计剩余"不能同时为0'); //分配预计剩余为0工时团队后任务
r($task->updateTeamTest($taskIDList[0], $taskStatusList[0], array($teamList[0], $teamList[2]), array($teamList[0], $teamList[1]), $teamEstimateList = array(1, 2.5), $teamConsumedList = array(0, 0), $teamLeftList = array(1, 0.5), true))                        && p(1)                 && e('user3');                             //改变团队第二个成员后任务团队
r($task->updateTeamTest($taskIDList[0], $taskStatusList[0], array($teamList[0], $teamList[2], $teamList[1]), array($teamList[0], $teamList[2]), $teamEstimateList = array(1, 2.5, 3), $teamConsumedList = array(0, 0, 0), $teamLeftList = array(1, 0.5, 3), true)) && p(2)                 && e('user2');                             //增加一个成员后任务团队
