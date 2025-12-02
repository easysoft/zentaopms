#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

/**

title=- 无消耗工时给出提示 @『ID \
timeout=0
cid=18865

- 以admin账号登录，构造待创建的工时信息
 - 第1条的consumed属性 @5
 - 第1条的left属性 @5
 - 第1条的task属性 @1
 - 第1条的account属性 @admin

- 以admin账号登录，构造待创建的工时信息
 - 第1条的consumed属性 @5
 - 第1条的left属性 @5
 - 第1条的task属性 @2
 - 第1条的account属性 @admin

- 以admin账号登录，构造待创建的工时信息
 - 第1条的consumed属性 @5
 - 第1条的left属性 @0
 - 第1条的task属性 @3
 - 第1条的account属性 @admin

- 以admin账号登录，构造待创建的工时信息
 - 第1条的consumed属性 @5
 - 第1条的left属性 @0
 - 第1条的task属性 @4
 - 第1条的account属性 @admin

- 以user1账号登录，构造待创建的工时信息
 - 第1条的consumed属性 @5
 - 第1条的left属性 @5
 - 第1条的task属性 @5
 - 第1条的account属性 @user1

- 以user1账号登录，构造待创建的工时信息
 - 第1条的consumed属性 @5
 - 第1条的left属性 @5
 - 第1条的task属性 @6
 - 第1条的account属性 @user1

*/
$task = zenData('task');
$task->id->range('1-7');
$task->execution->range('1-7');
$task->name->prefix("任务")->range('1-7');
$task->left->range('1-7');
$task->mode->range(" , multi, , , , ,");
$task->estStarted->range('2022\-01\-01');
$task->assignedTo->prefix("user")->range('1-7');
$task->status->range("wait,wait,doing,done,pause,cancel,closed");
$task->gen(7);

$taskteam = zenData('taskteam');
$taskteam->id->range('1-5');
$taskteam->task->range('2');
$taskteam->account->prefix("user")->range('1-5');
$taskteam->estimate->range('5');
$taskteam->consumed->range('0');
$taskteam->left->range('5');
$taskteam->status->range("wait");
$taskteam->gen(5);

$effort = zenData('effort');
$effort->gen(1);

$user = zenData('user');
$user->gen(20);

$action = zenData('action');
$action->gen(0);

$finishRecord = array();
$finishRecord[1] = new stdclass();
$finishRecord[1]->consumed = 5;
$finishRecord[1]->left     = 0;
$finishRecord[1]->work     = "完成了任务";
$finishRecord[1]->date     = "2022-01-01";

$startRecord = array();
$startRecord[1] = new stdclass();
$startRecord[1]->consumed = 5;
$startRecord[1]->left     = 5;
$startRecord[1]->work     = "开始了任务";
$startRecord[1]->date     = "2022-01-01";

$normalRecord = array();
$normalRecord[1] = new stdclass();
$normalRecord[1]->consumed = 5;
$normalRecord[1]->left     = 5;
$normalRecord[1]->work     = "记录了日志";
$normalRecord[1]->date     = "2022-01-01";

$nodateRecord = array();
$nodateRecord[1] = new stdclass();
$nodateRecord[1]->consumed = 5;
$nodateRecord[1]->left     = 5;
$nodateRecord[1]->work     = "记录了日志";
$nodateRecord[1]->date     = "2022-01-01";

$task = new taskTest();
r($task->objectModel->buildWorkhour(1, $startRecord))  && p('1:consumed,left,task,account') && e('5,5,1,admin'); // 以admin账号登录，构造待创建的工时信息 
r($task->objectModel->buildWorkhour(2, $startRecord))  && p('1:consumed,left,task,account') && e('5,5,2,admin'); // 以admin账号登录，构造待创建的工时信息 
r($task->objectModel->buildWorkhour(3, $finishRecord)) && p('1:consumed,left,task,account') && e('5,0,3,admin'); // 以admin账号登录，构造待创建的工时信息
r($task->objectModel->buildWorkhour(4, $finishRecord)) && p('1:consumed,left,task,account') && e('5,0,4,admin'); // 以admin账号登录，构造待创建的工时信息 

su('user1');
r($task->objectModel->buildWorkhour(5, $normalRecord)) && p('1:consumed,left,task,account') && e('5,5,5,user1'); // 以user1账号登录，构造待创建的工时信息 
r($task->objectModel->buildWorkhour(6, $normalRecord)) && p('1:consumed,left,task,account') && e('5,5,6,user1'); // 以user1账号登录，构造待创建的工时信息