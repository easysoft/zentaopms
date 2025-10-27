#!/usr/bin/env php
<?php

/**

title=编辑任务
timeout=0
cid=1

- 编辑开发任务
 - 测试结果 @成功编辑开发任务
- 编辑任务名称设置为空
 - 测试结果 @任务名称不能为空

*/
chdir(__DIR__);
include '../lib/ui/edittask.ui.class.php';
global $config;

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin, user1, user2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->realname->range('admin, 用户1, 用户2');
$user->gen(3);

$product = zenData('product');
$product->id->range('1');
$product->name->range('李娟的产品');
$product->gen(1);

$project = zenData('project');
$project->id->range('1, 2');
$project->project->range('0, 1');
$project->model->range('scrum, []');
$project->type->range('project, sprint');
$project->auth->range('extend, []');
$project->storyType->range('story');
$project->parent->range('0, 1');
$project->path->range('`,1,`, `,1,2,`');
$project->name->range('李娟的项目, 李娟的执行');
$project->gen(2);

$task = zenData('task');
$task->id->range('1, 2 ');
$task->project->range('1');
$task->execution->range('2');
$task->name->range('李娟的开发任务1, 李娟的开发任务2');
$task->type->range('devel');
$task->story->range('0');
$task->storyVersion->range('0');
$task->module->range('0');
$task->estimate->range('3');
$task->consumed->range('2');
$task->left->range('1');
$task->status->range('doing');
$task->assignedTo->range('用户1, 用户2');
$task->gen(2);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1, 2');
$taskSpec->version->range('1');
$taskSpec->name->range('李娟的开发任务1, 李娟的开发任务2');
$taskSpec->gen(2);

$team = zenData('team');
$team->id->range('1,2,3');
$team->root->range('2');
$team->type->range('execution');
$team->account->range('admin, user1, user2');
$team->gen(3);

$tester = new editTaskTester();
$tester->login();

$task = new stdclass();
$task->name       = '开发任务';
$task->type       = '设计';
$task->assignedTo = '用户2';

$task2 = new stdclass();
$task2->name = '';

r($tester->editDevTask($task)) && p('message') && e('成功编辑开发任务');           // 编辑开发任务
r($tester->editNameBlankTask($task2)) && p('message') && e('任务名称不能为空');    // 编辑任务设置名称为空

$tester->closeBrowser();
