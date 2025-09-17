#!/usr/bin/env php
<?php

/**

title=关闭任务
timeout=0
cid=1

- 执行tester模块的close方法，参数是'1', 'wait'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @没有显示关闭按钮
- 执行tester模块的close方法，参数是'2', 'doing'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @没有显示关闭按钮
- 执行tester模块的close方法，参数是'3', 'done'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @关闭任务成功
- 执行tester模块的close方法，参数是'4', 'pause'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @没有显示关闭按钮
- 执行tester模块的close方法，参数是'5', 'cancel'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @关闭任务成功
- 执行tester模块的close方法，参数是'6', 'closed'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @没有显示关闭按钮

*/

chdir(__DIR__);
include '../lib/ui/closetask.ui.class.php';

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
$task->gen(6);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('0');
$taskSpec->name->range('1-100');
$taskSpec->gen(6);

$tester = new closeTaskTester();
$tester->login();

r($tester->close('1', 'wait'))   &&p('status,message') &&e('SUCCESS,没有显示关闭按钮');
r($tester->close('2', 'doing'))  &&p('status,message') &&e('SUCCESS,没有显示关闭按钮');
r($tester->close('3', 'done'))   &&p('status,message') &&e('SUCCESS,关闭任务成功');
r($tester->close('4', 'pause'))  &&p('status,message') &&e('SUCCESS,没有显示关闭按钮');
r($tester->close('5', 'cancel')) &&p('status,message') &&e('SUCCESS,关闭任务成功');
r($tester->close('6', 'closed')) &&p('status,message') &&e('SUCCESS,没有显示关闭按钮');
$tester->closeBrowser();
