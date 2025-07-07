#!/usr/bin/env php
<?php

/**

title=批量创建任务
timeout=0
cid=1

- 批量创建任务
 - 测试结果 @成功批量创建任务
- 批量创建名称为空的任务
 - 测试结果 @保存成功

*/
chdir(__DIR__);
include '../lib/batchcreatetask.ui.class.php';
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

$team = zenData('team');
$team->id->range('1,2,3');
$team->root->range('2');
$team->type->range('execution');
$team->account->range('admin, user1, user2');
$team->gen(3);
