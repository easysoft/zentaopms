#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('task')->loadYaml('task')->gen(9);
zenData('taskteam')->loadYaml('taskteam')->gen(6);

/**

title=taskModel->getTeamByTask();
timeout=0
cid=18822

- 获取taskID=1的任务团队成员信息 @0
- 获取taskID=2的任务团队成员信息 @0
- 获取taskID=3的任务团队成员信息 @0
- 获取taskID=4的任务团队成员信息 @0
- 获取taskID=5的任务团队成员信息 @0
- 获取taskID=6的任务团队成员信息 @0
- 获取taskID=7的任务团队成员信息 @0
- 获取taskID=8的任务团队成员信息
 - 第1条的account属性 @admin
 - 第1条的estimate属性 @1.00
 - 第1条的status属性 @wait
- 获取taskID=8的任务团队成员数量 @3
- 获取taskID=9的任务团队成员信息
 - 第4条的account属性 @admin
 - 第4条的estimate属性 @4.00
 - 第4条的status属性 @wait
- 获取taskID=9的任务团队成员数量 @3
- 获取不存在的taskID=10的任务团队成员信息 @0
- 获取taskID=8的任务团队成员信息id @3,2,1

- 获取taskID=9的任务团队成员信息id @6,5,4

*/

$taskIdList = range(1, 10);

$taskModel = $tester->loadModel('task');
r($taskModel->getTeamByTask($taskIdList[0]))        && p()                            && e('0');               // 获取taskID=1的任务团队成员信息
r($taskModel->getTeamByTask($taskIdList[1]))        && p()                            && e('0');               // 获取taskID=2的任务团队成员信息
r($taskModel->getTeamByTask($taskIdList[2]))        && p()                            && e('0');               // 获取taskID=3的任务团队成员信息
r($taskModel->getTeamByTask($taskIdList[3]))        && p()                            && e('0');               // 获取taskID=4的任务团队成员信息
r($taskModel->getTeamByTask($taskIdList[4]))        && p()                            && e('0');               // 获取taskID=5的任务团队成员信息
r($taskModel->getTeamByTask($taskIdList[5]))        && p()                            && e('0');               // 获取taskID=6的任务团队成员信息
r($taskModel->getTeamByTask($taskIdList[6]))        && p()                            && e('0');               // 获取taskID=7的任务团队成员信息
r($taskModel->getTeamByTask($taskIdList[7]))        && p('1:account,estimate,status') && e('admin,1.00,wait'); // 获取taskID=8的任务团队成员信息
r(count($taskModel->getTeamByTask($taskIdList[7]))) && p()                            && e('3');               // 获取taskID=8的任务团队成员数量
r($taskModel->getTeamByTask($taskIdList[8]))        && p('4:account,estimate,status') && e('admin,4.00,wait'); // 获取taskID=9的任务团队成员信息
r(count($taskModel->getTeamByTask($taskIdList[8]))) && p()                            && e('3');               // 获取taskID=9的任务团队成员数量
r($taskModel->getTeamByTask($taskIdList[9]))        && p()                            && e('0');               // 获取不存在的taskID=10的任务团队成员信息

r(implode(',', array_keys($taskModel->getTeamByTask($taskIdList[7], 'order_desc')))) && p() && e('3,2,1'); // 获取taskID=8的任务团队成员信息id
r(implode(',', array_keys($taskModel->getTeamByTask($taskIdList[8], 'order_desc')))) && p() && e('6,5,4'); // 获取taskID=9的任务团队成员信息id
