#!/usr/bin/env php
<?php

/**

title=完成任务
timeout=0
cid=1

- 执行tester模块的finish方法，参数是'1', '0', '', 'wait'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @任务本次消耗为0时提示正确
- 执行tester模块的finish方法，参数是'2', '1', '', 'wait'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @完成任务成功
- 执行tester模块的finish方法，参数是'3', '0', 'USER1', 'doing'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @完成任务成功
- 执行tester模块的finish方法，参数是'4', '1', '', 'doing'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @完成任务成功
- 执行tester模块的finish方法，参数是'5', '', '', 'done'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @没有显示完成按钮
- 执行tester模块的finish方法，参数是'6', '0', '', 'pause'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @完成任务成功
- 执行tester模块的finish方法，参数是'7', '1', 'USER1', 'pause'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @完成任务成功
- 执行tester模块的finish方法，参数是'8', '', '', 'cancel'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @没有显示完成按钮
- 执行tester模块的finish方法，参数是'9', '', '', 'closed'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @没有显示完成按钮

*/

chdir(__DIR__);
include '../lib/ui/finishtask.ui.class.php';

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
$task->parent->range('0 ');
$task->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,7,`, `,8,`, `,9,`');
$task->execution->range('2');
$task->story->range('0');
$task->name->range('1-100');
$task->type->range('devel');
$task->consumed->range('0, 2{8}');
$task->left->range('1{4}, 0, 1{4}');
$task->deadline->range(' (-5D)-(-4D):1D, []{11}')->type('timestamp')->format('YY/MM/DD');
$task->status->range('wait{2}, doing{2}, done, pause{2}, cancel, closed');
$task->gen(9);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('0');
$taskSpec->name->range('1-100');
$taskSpec->gen(9);

$team = zenData('team');
$team->id->range('1-100');
$team->root->range('1{2}, 2{2}');
$team->type->range('project{2}, execution{2}');
$team->account->range('admin, user1');
$team->gen('4');

$tester = new finishTaskTester();
$tester->login();

r($tester->finish('1', '0', '', 'wait'))       &&p('status,message') &&e('SUCCESS,任务本次消耗为0时提示正确');
r($tester->finish('2', '1', '', 'wait'))       &&p('status,message') &&e('SUCCESS,完成任务成功');
r($tester->finish('3', '0', 'USER1', 'doing')) &&p('status,message') &&e('SUCCESS,完成任务成功');
r($tester->finish('4', '1', '', 'doing'))      &&p('status,message') &&e('SUCCESS,完成任务成功');
r($tester->finish('5', '', '', 'done'))        &&p('status,message') &&e('SUCCESS,没有显示完成按钮');
r($tester->finish('6', '0', '', 'pause'))      &&p('status,message') &&e('SUCCESS,完成任务成功');
r($tester->finish('7', '1', 'USER1', 'pause')) &&p('status,message') &&e('SUCCESS,完成任务成功');
r($tester->finish('8', '', '', 'cancel'))      &&p('status,message') &&e('SUCCESS,没有显示完成按钮');
r($tester->finish('9', '', '', 'closed'))      &&p('status,message') &&e('SUCCESS,没有显示完成按钮');
$tester->closeBrowser();
