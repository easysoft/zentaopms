#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('project')->loadYaml('execution')->gen(500);
zenData('task')->gen(2);

/**

title=测试 commonModel::getUserPriv();
timeout=0
cid=73149

- 执行已关闭且禁止修改时任务批量关闭无权限 @0
- 执行已关闭且禁止修改时任务关闭无权限 @0
- 执行已关闭且禁止修改时任务编辑无权限 @0
- 执行已关闭且允许修改时任务批量关闭有权限 @1
- 执行未关闭时任务批量关闭有权限 @1

*/

global $tester, $app, $config;

$closedTask = $tester->loadModel('task')->fetchById(1);
$openTask   = $tester->loadModel('task')->fetchById(2);
$tester->dao->update(TABLE_EXECUTION)->set('status')->eq('closed')->where('id')->eq($closedTask->execution)->exec();
$tester->dao->update(TABLE_EXECUTION)->set('status')->eq('doing')->where('id')->eq($openTask->execution)->exec();

su('user1');
$app->user->admin = '';
$app->user->rights = array(
    'rights' => array('task' => array('batchclose' => 1, 'close' => 1, 'edit' => 1)),
    'acls'   => array(),
    'executions' => ''
);

$config->CRExecution = 0;

r(commonModel::getUserPriv('task', 'batchClose', $closedTask)) && p() && e('0'); // 执行已关闭且禁止修改时任务批量关闭无权限
r(commonModel::getUserPriv('task', 'close', $closedTask))       && p() && e('0'); // 执行已关闭且禁止修改时任务关闭无权限
r(commonModel::getUserPriv('task', 'edit', $closedTask))       && p() && e('0'); // 执行已关闭且禁止修改时任务编辑无权限

$config->CRExecution = 1;
r(commonModel::getUserPriv('task', 'batchClose', $closedTask)) && p() && e('1'); // 执行已关闭且允许修改时任务批量关闭有权限

$config->CRExecution = 0;
r(commonModel::getUserPriv('task', 'batchClose', $openTask)) && p() && e('1'); // 执行未关闭时任务批量关闭有权限
