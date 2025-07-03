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
include '../lib/edittask.ui.class.php';
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
