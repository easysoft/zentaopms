#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=taskModel->manageTaskTeamMember();
cid=18832
pid=1

*/

$modeList = array('linear', 'multi');

$taskIdList     = array(1, 2, 3);
$taskStatusList = array('wait', 'doing', 'done');
foreach($taskStatusList as $index => $status)
{
    $taskName  = "{$status}Task";
    $$taskName = new stdclass();
    $$taskName->id     = $taskIdList[$index];
    $$taskName->status = $status;
}

$account          = array(array('admin', 'user1', 'user2', 'user3'), array('user4', 'user5', 'user6', 'user7', 'user9'));
$teamEstimateList = array(array(1, 2, 3, 4), array(2, 3, 4, 5, 7));
$teamConsumedList = array(array(4, 3, 2, 1), array(4, 3, 2, 1, 0));
$teamLeftList     = array(array(0, 0, 0, 0), array(0, 1, 3, 5, 7));
$teamSourceList   = array(array('admin', 'user1', 'user2', 'user3'), array('user4', 'user5', 'user6', 'user7', 'user9'));

$teamData1 = new stdclass();
$teamData1->team         = $account[0];
$teamData1->teamLeft     = $teamLeftList[0];
$teamData1->teamSource   = $teamSourceList[0];
$teamData1->teamEstimate = $teamEstimateList[0];
$teamData1->teamConsumed = $teamConsumedList[0];

$teamData2 = new stdclass();
$teamData2->team         = $account[1];
$teamData2->teamLeft     = $teamLeftList[1];
$teamData2->teamSource   = $teamSourceList[1];
$teamData2->teamEstimate = $teamEstimateList[1];
$teamData2->teamConsumed = $teamConsumedList[1];

$task = new taskModelTest();

r($task->manageTaskTeamMemberTest($modeList[0], $waitTask,  $teamData1)) && p('taskTeamMember') && e('1|admin|1.00|4.00|1.00||wait'); // 测试更新任务状态为 wait  的串行多人任务内用户 admin 不存在消耗和剩余的 在用户列表中的成员信息
r($task->manageTaskTeamMemberTest($modeList[0], $doingTask, $teamData1)) && p('taskTeamMember') && e('2|admin|1.00|4.00|0.00||done'); // 测试更新任务状态为 doing 的串行多人任务内用户 admin 不存在消耗和剩余的 不在未完成任务列表 在用户列表中的成员信息
r($task->manageTaskTeamMemberTest($modeList[0], $doneTask,  $teamData1)) && p('taskTeamMember') && e('3|admin|1.00|4.00|0.00||done'); // 测试更新任务状态为 done  的串行多人任务内用户 admin 不存在消耗和剩余的 不在未完成任务列表 在用户列表中的成员信息

r($task->manageTaskTeamMemberTest($modeList[0], $waitTask,  $teamData2)) && p('taskTeamMember') && e('1|user4|2.00|4.00|2.00||wait'); // 测试更新任务状态为 wait  的串行多人任务内用户 user4 存在消耗和剩余的 在用户列表中的成员信息
r($task->manageTaskTeamMemberTest($modeList[0], $doingTask, $teamData2)) && p('taskTeamMember') && e('2|user4|2.00|4.00|0.00||done'); // 测试更新任务状态为 doing 的串行多人任务内用户 user4 存在消耗和剩余的 在用户列表中的成员信息
r($task->manageTaskTeamMemberTest($modeList[0], $doneTask,  $teamData2)) && p('taskTeamMember') && e('3|user4|2.00|4.00|0.00||done'); // 测试更新任务状态为 done  的串行多人任务内用户 user4 存在消耗和剩余的 在用户列表中的成员信息

r($task->manageTaskTeamMemberTest($modeList[1], $waitTask,  $teamData1)) && p('taskTeamMember') && e('1|admin|1.00|4.00|1.00||wait'); // 测试更新任务状态为 wait  的并行多人任务内用户 admin 不存在消耗和剩余的 在用户列表中的成员信息
r($task->manageTaskTeamMemberTest($modeList[1], $doingTask, $teamData1)) && p('taskTeamMember') && e('2|admin|1.00|4.00|0.00||done'); // 测试更新任务状态为 doing 的并行多人任务内用户 admin 不存在消耗和剩余的 不在未完成任务列表 在用户列表中的成员信息
r($task->manageTaskTeamMemberTest($modeList[1], $doneTask,  $teamData1)) && p('taskTeamMember') && e('3|admin|1.00|4.00|0.00||done'); // 测试更新任务状态为 done  的并行多人任务内用户 admin 不存在消耗和剩余的 在未完成任务列表   在用户列表中的成员信息

r($task->manageTaskTeamMemberTest($modeList[1], $waitTask,  $teamData2)) && p('taskTeamMember') && e('1|user4|2.00|4.00|2.00||wait'); // 测试更新任务状态为 wait  的并行多人任务内用户 user4 存在消耗和剩余的 在用户列表中的成员信息
r($task->manageTaskTeamMemberTest($modeList[1], $doingTask, $teamData2)) && p('taskTeamMember') && e('2|user4|2.00|4.00|0.00||done'); // 测试更新任务状态为 doing 的并行多人任务内用户 user4 存在消耗和剩余的 在用户列表中的成员信息
r($task->manageTaskTeamMemberTest($modeList[1], $doneTask,  $teamData2)) && p('taskTeamMember') && e('3|user4|2.00|4.00|0.00||done'); // 测试更新任务状态为 done  的并行多人任务内用户 user4 存在消耗和剩余的 在用户列表中的成员信息
