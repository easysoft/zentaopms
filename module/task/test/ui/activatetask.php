#!/usr/bin/env php
<?php

/**

title=激活任务
timeout=0
cid=1

- 执行tester模块的activate方法，参数是'1', '', '', 'wait'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @没有显示激活按钮
- 执行tester模块的activate方法，参数是'2', '', '', 'doing'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @没有显示激活按钮
- 执行tester模块的activate方法，参数是'3', '', '', 'done'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @预计剩余为空或0时提示正确
- 执行tester模块的activate方法，参数是'3', '', '0', 'done'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @预计剩余为空或0时提示正确
- 执行tester模块的activate方法，参数是'3', 'USER1', '1', 'done'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @激活任务成功
- 执行tester模块的activate方法，参数是'4', '', '', 'pause'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @没有显示激活按钮
- 执行tester模块的activate方法，参数是'5', 'admin', '2', 'cancel'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @激活任务成功
- 执行tester模块的activate方法，参数是'6', '', '3', 'closed'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @激活任务成功

*/

chdir(__DIR__);
include '../lib/ui/activatetask.ui.class.php';

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin, user1, user2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->realname->range('admin, USER1, USER2');
$user->gen(3);

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->gen(1);

$project = zenData('project');
$project->id->range('1, 2');
$project->project->range('0, 1');
$project->model->range('scrum, []');
$project->type->range('project, sprint');
$project->auth->range('extend, []');
$project->parent->range('0, 1');
$project->path->range('`,1,`, `,1,2,`');
$project->name->range('项目1, 项目1执行1');
$project->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1, 2');
$projectProduct->product->range('1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(2);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('1');
$task->parent->range('0');
$task->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,7,`, `,8,`, `,9,`, `,10,`');
$task->execution->range('2');
$task->story->range('0');
$task->name->range('1-100');
$task->type->range('devel');
$task->consumed->range('0, 2{9}');
$task->left->range('0, 1, 0, 1{7}');
$task->deadline->range(' (-5D)-(-4D):1D, []{11}')->type('timestamp')->format('YY/MM/DD');
$task->status->range('wait, doing, done, pause, cancel, closed');
$task->gen(6);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('0');
$taskSpec->name->range('1-100');
$taskSpec->gen(6);

$team = zenData('team');
$team->id->range('1-100');
$team->root->range('1{2}, 2{2}');
$team->type->range('project{2}, execution{2}');
$team->account->range('admin, user1');
$team->gen('4');

$tester = new activateTaskTester();
$tester->login();

r($tester->activate('1', '', '', 'wait'))         &&p('status,message') &&e('SUCCESS,没有显示激活按钮');
r($tester->activate('2', '', '', 'doing'))        &&p('status,message') &&e('SUCCESS,没有显示激活按钮');
r($tester->activate('3', '', '', 'done'))         &&p('status,message') &&e('SUCCESS,预计剩余为空或0时提示正确');
r($tester->activate('3', '', '0', 'done'))        &&p('status,message') &&e('SUCCESS,预计剩余为空或0时提示正确');
r($tester->activate('3', 'USER1', '1', 'done'))   &&p('status,message') &&e('SUCCESS,激活任务成功');
r($tester->activate('4', '', '', 'pause'))        &&p('status,message') &&e('SUCCESS,没有显示激活按钮');
r($tester->activate('5', 'admin', '2', 'cancel')) &&p('status,message') &&e('SUCCESS,激活任务成功');
r($tester->activate('6', '', '3', 'closed'))      &&p('status,message') &&e('SUCCESS,激活任务成功');
$tester->closeBrowser();
