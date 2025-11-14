#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

/**

title=taskModel->getTeamByAccount();
timeout=0
cid=18821

- 获取未开始的指定账号的团队信息 @admin_wait
- 获取进行中的指定账号的团队信息 @admin_doing
- 过滤已完成的成员获取的指定账号的团队信息 @admin_wait
- 获取不存在的账号 @_
- 不过滤已完成的成员获取的指定账号的团队信息 @admin_done
- 根据传入的日志ID获取对应成员的团队信息 @dev1_wait
- 根据传入的日志ID获取对应成员的团队信息 @dev2_done

*/

$task = zenData('task');
$task->id->range('1-5');
$task->execution->range('1-5');
$task->name->prefix("任务")->range('1-5');
$task->left->range('11-15');
$task->estStarted->range('2022\-01\-01');
$task->status->range("wait,doing,done,pause,cancel,closed");
$task->gen(5);

$effort = zenData('effort');
$effort->id->range('1-5');
$effort->objectID->range('1-5');
$effort->objectType->range('task');
$effort->execution->range('1-5');
$effort->work->prefix("工作内容")->range('1-5');
$effort->left->range('11-15');
$effort->consumed->range('11-15');
$effort->date->range('2022\-01\-01');
$effort->account->range("admin,dev1,dev2");
$effort->gen(5);

$user = new stdclass();
$user->task    = 1;
$user->account = 'admin';
$user->status  = 'wait';
$user->order   = '0';
$users[] = $user;

$user = new stdclass();
$user->task    = 1;
$user->account = 'dev1';
$user->status  = 'wait';
$user->order   = '1';
$users[] = $user;

$user = new stdclass();
$user->task    = 1;
$user->account = 'admin';
$user->status  = 'wait';
$user->order   = '2';
$users[] = $user;

$user = new stdclass();
$user->task    = 1;
$user->account = 'dev2';
$user->status  = 'done';
$user->order   = '3';
$users[] = $user;

$user = new stdclass();
$user->task    = 1;
$user->account = 'admin';
$user->status  = 'done';
$user->order   = '4';
$users[] = $user;

$task = new taskTest();
r($task->getTeamByAccountTest($users, 'admin')) && p() && e('admin_wait'); //获取未开始的指定账号的团队信息

$users[0]->status = 'doing';
r($task->getTeamByAccountTest($users, 'admin')) && p() && e('admin_doing'); //获取进行中的指定账号的团队信息

$users[0]->status = 'done';
r($task->getTeamByAccountTest($users, 'admin'))                                         && p() && e('admin_wait'); // 过滤已完成的成员获取的指定账号的团队信息
r($task->getTeamByAccountTest($users, 'dev'))                                           && p() && e('_');          // 获取不存在的账号
r($task->getTeamByAccountTest($users, 'admin', array('filter' => '')))                  && p() && e('admin_done'); // 不过滤已完成的成员获取的指定账号的团队信息
r($task->getTeamByAccountTest($users, 'dev1',  array('filter' => '', 'effortID' => 3))) && p() && e('dev1_wait');  // 根据传入的日志ID获取对应成员的团队信息
r($task->getTeamByAccountTest($users, 'dev2',  array('filter' => '')))                  && p() && e('dev2_done');  // 根据传入的日志ID获取对应成员的团队信息