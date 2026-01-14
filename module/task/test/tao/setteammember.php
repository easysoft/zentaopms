#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('taskteam')->gen(0);

/**

title=taskModel->batchComputeProgress();
timeout=0
cid=18890

*/

$memberList[] = array('task' => 1, 'account' => 'admin', 'estimate' => 1, 'consumed' => 0, 'left' => 1, 'status' => 'wait');
$memberList[] = array('task' => 1, 'account' => 'user1', 'estimate' => 1, 'consumed' => 1, 'left' => 1, 'status' => 'doing');
$memberList[] = array('task' => 1, 'account' => 'user2', 'estimate' => 1, 'consumed' => 1, 'left' => 0, 'status' => 'done');
$memberList[] = array('task' => 2, 'account' => 'admin', 'estimate' => 1, 'consumed' => 0, 'left' => 1, 'status' => 'wait');
$memberList[] = array('task' => 2, 'account' => 'user1', 'estimate' => 1, 'consumed' => 1, 'left' => 1, 'status' => 'doing');
$memberList[] = array('task' => 2, 'account' => 'user2', 'estimate' => 1, 'consumed' => 1, 'left' => 0, 'status' => 'done');

$taskTester = new taskTaoTest();
r($taskTester->setTeamMemberObject($memberList[0], 'linear'))       && p('account,estimate,status') && e('admin,1.00,wait');  // 创建串行多人任务的成员信息
r($taskTester->setTeamMemberObject($memberList[1], 'linear'))       && p('account,estimate,status') && e('user1,1.00,doing'); // 创建串行多人任务的成员信息
r($taskTester->setTeamMemberObject($memberList[2], 'linear'))       && p('account,estimate,status') && e('user2,1.00,done');  // 创建串行多人任务的成员信息
r($taskTester->setTeamMemberObject($memberList[3], 'multi'))        && p('account,estimate,status') && e('admin,1.00,wait');  // 创建并行多人任务的成员信息
r($taskTester->setTeamMemberObject($memberList[4], 'multi'))        && p('account,estimate,status') && e('user1,1.00,doing'); // 创建并行多人任务的成员信息
r($taskTester->setTeamMemberObject($memberList[5], 'multi'))        && p('account,estimate,status') && e('user2,1.00,done');  // 创建并行多人任务的成员信息
r($taskTester->setTeamMemberObject($memberList[3], 'multi', true))  && p('account,estimate,status') && e('admin,2.00,wait');  // 更新并行多人任务的成员信息
r($taskTester->setTeamMemberObject($memberList[4], 'multi', true))  && p('account,estimate,status') && e('user1,2.00,doing'); // 更新并行多人任务的成员信息
r($taskTester->setTeamMemberObject($memberList[5], 'multi', true))  && p('account,estimate,status') && e('user2,2.00,done');  // 更新并行多人任务的成员信息
