#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$task = zdTable('task');
$task->id->range('1-7');
$task->execution->range('1-7');
$task->name->prefix("任务")->range('1-7');
$task->left->range('1-7');
$task->mode->range(" , multi, , , , ,");
$task->estStarted->range('2022\-01\-01');
$task->assignedTo->prefix("user")->range('1-7');
$task->status->range("wait,wait,doing,done,pause,cancel,closed");
$task->gen(4);

$taskteam = zdTable('taskteam');
$taskteam->id->range('1-20');
$taskteam->task->range('1-4');
$taskteam->account->prefix("user")->range('1-5');
$taskteam->estimate->range('5');
$taskteam->consumed->range('0,1');
$taskteam->left->range('5');
$taskteam->status->range("wait");
$taskteam->gen(20);

$user = zdTable('user');
$user->gen(20);

/**

title=taskModel->getTeamMembersByIdList();
timeout=0
cid=1

- 只有4个任务有团队信息 @4

- 每个任务团队有5个人 @5

- 每个任务团队有5个人 @5

- 每个任务团队有5个人 @5

- 每个任务团队有5个人 @5

- 查看任务1的团队成员的第4个人
 - 第4条的account属性 @user2
 - 第4条的estimate属性 @5.00
 - 第4条的status属性 @wait
 - 第4条的consumed属性 @0.00

- 查看任务1的团队成员的第4个人
 - 第4条的account属性 @user3
 - 第4条的estimate属性 @5.00
 - 第4条的status属性 @wait
 - 第4条的consumed属性 @1.00

- 查看任务1的团队成员的第4个人
 - 第4条的account属性 @user4
 - 第4条的estimate属性 @5.00
 - 第4条的status属性 @wait
 - 第4条的consumed属性 @0.00

*/

$taskIdList = array(1,2,3,4,5,6,7,8,9,10,0);

$taskModel   = $tester->loadModel('task');
$memberGroup = $taskModel->getTeamMembersByIdList($taskIdList);
r(count($memberGroup))    && p() && e('4'); // 只有4个任务有团队信息
r(count($memberGroup[1])) && p() && e('5'); // 每个任务团队有5个人
r(count($memberGroup[2])) && p() && e('5'); // 每个任务团队有5个人
r(count($memberGroup[3])) && p() && e('5'); // 每个任务团队有5个人
r(count($memberGroup[4])) && p() && e('5'); // 每个任务团队有5个人
r($memberGroup[1]) && p('4:account,estimate,status,consumed') && e('user2,5.00,wait,0.00'); // 查看任务1的团队成员的第4个人
r($memberGroup[2]) && p('4:account,estimate,status,consumed') && e('user3,5.00,wait,1.00'); // 查看任务1的团队成员的第4个人
r($memberGroup[3]) && p('4:account,estimate,status,consumed') && e('user4,5.00,wait,0.00'); // 查看任务1的团队成员的第4个人
