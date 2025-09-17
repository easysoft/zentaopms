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
include '../lib/ui/batchcreatetask.ui.class.php';
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

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1, 2');
$projectProduct->product->range('1');
$projectProduct->gen(2);

$story = zenData('story');
$story->id->range('1');
$story->path->range('`,1,`');
$story->product->range('1');
$story->title->range('李娟的需求版本2');
$story->type->range('story');
$story->status->range('active');
$story->version->range('2');
$story->gen(1);

$storySpec = zenData('storyspec');
$storySpec->story->range('1');
$storySpec->version->range('1,2');
$storySpec->title->range('李娟的需求版本1, 李娟的需求版本2');
$storySpec->gen(2);

$projectStory = zenData('projectstory');
$projectStory->project->range('1, 2');
$projectStory->product->range('1');
$projectStory->story->range('1');
$projectStory->version->range('1');
$projectStory->gen(2);

$tester = new batchCreateTaskTester();
$tester->login();

$task = new stdclass();
$task->name     = '李娟批量创建的开发任务';
$task->estimate = '2';

$task1 = new stdclass();
$task1->name = '';

r($tester->batchCreateTask($task)) && p('message') && e('成功批量创建任务');       //成功批量创建任务
r($tester->batchCreateNameBlankTask($task1)) && p('message') && e('保存成功');     //保存成功

$tester->closeBrowser();
