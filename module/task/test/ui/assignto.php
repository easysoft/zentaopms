#!/usr/bin/env php
<?php

/**

title=指派任务
timeout=0
cid=1

- 执行tester模块的assignTo方法，参数是'1', 'USER1', ''▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @预计剩余内容错误提示正确
- 执行tester模块的assignTo方法，参数是'1', 'USER1', '0'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @预计剩余内容错误提示正确
- 执行tester模块的assignTo方法，参数是'1', 'USER1', 'aaa'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @预计剩余内容错误提示正确
- 执行tester模块的assignTo方法，参数是'1', 'USER1', '1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @指派任务成功
- 执行tester模块的assignTo方法，参数是'2', 'USER1', '1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @指派任务成功
- 执行tester模块的assignTo方法，参数是'3', 'USER1', '1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @指派任务成功
- 执行tester模块的assignTo方法，参数是'4', '', '1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @指派任务成功
- 执行tester模块的assignTo方法，参数是'5', '', '1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @已取消的任务详情页没有显示指派按钮
- 执行tester模块的assignTo方法，参数是'6', 'USER1', '1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @已关闭的任务没有显示指派按钮

*/

chdir(__DIR__);
include '../lib/ui/assignto.ui.class.php';

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
$task->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`');
$task->execution->range('2');
$task->story->range('0');
$task->name->range('1-100');
$task->type->range('devel');
$task->consumed->range('0');
$task->left->range('0');
$task->deadline->range(' (-5D)-(-4D):1D, []{11}')->type('timestamp')->format('YY/MM/DD');
$task->status->range('wait, doing, done, pause, cancel, closed');
$task->openedBy->range('admin');
$task->assignedTo->range('[], admin{4}, closed');
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

$tester = new assignToTester();
$tester->login();

r($tester->assignTo('1', 'USER1', ''))    &&p('status,message') &&e('SUCCESS,预计剩余内容错误提示正确');
r($tester->assignTo('1', 'USER1', '0'))   &&p('status,message') &&e('SUCCESS,预计剩余内容错误提示正确');
r($tester->assignTo('1', 'USER1', 'aaa')) &&p('status,message') &&e('SUCCESS,预计剩余内容错误提示正确');
r($tester->assignTo('1', 'USER1', '1'))   &&p('status,message') &&e('SUCCESS,指派任务成功');
r($tester->assignTo('2', 'USER1', '1'))   &&p('status,message') &&e('SUCCESS,指派任务成功');
r($tester->assignTo('3', 'USER1', '1'))   &&p('status,message') &&e('SUCCESS,指派任务成功');
r($tester->assignTo('4', '', '1'))        &&p('status,message') &&e('SUCCESS,指派任务成功');
r($tester->assignTo('5', '', '1'))        &&p('status,message') &&e('SUCCESS,已取消的任务详情页没有显示指派按钮');
r($tester->assignTo('6', 'USER1', '1'))   &&p('status,message') &&e('SUCCESS,已关闭的任务没有显示指派按钮');
$tester->closeBrowser();
