#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->getTeamByAccount();
cid=1
pid=1

*/

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

$task = new taskTest();
r($task->getTeamByAccount($users, 'admin')) && p() && e('admin_wait'); //获取未开始的指定账号的团队信息

$users[0]->status = 'doing';
r($task->getTeamByAccount($users, 'admin')) && p() && e('admin_doing'); //获取进行中的指定账号的团队信息

$users[0]->status = 'done';
r($task->getTeamByAccount($users, 'admin')) && p() && e('admin_wait'); //过滤已完成的成员获取的指定账号的团队信息
r($task->getTeamByAccount($users, 'admin', array('filter' => ''))) && p() && e('admin_done'); //不过滤已完成的成员获取的指定账号的团队信息

r($task->getTeamByAccount($users, 'dev')) && p() && e('_'); //获取的不存在账号
