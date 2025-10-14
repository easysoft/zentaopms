#!/usr/bin/env php
<?php

/**

title=创建任务
timeout=0
cid=1

- 创建开发任务
 - 测试结果 @成功创建开发任务
- 创建设计任务
 - 测试结果 @成功创建设计任务
- 创建名称为空的任务
 - 测试结果 @任务名称不能为空
- 创建事务任务
 - 测试结果 @成功创建事务任务
- 创建测试任务
 -测试结果 @成功创建测试任务
- 创建多人串行任务
 - 测试结果  @成功创建多人串行任务
- 创建多人并行任务
 - 测试结果  @成功创建多人并行任务

*/
chdir(__DIR__);
include '../lib/ui/createtask.ui.class.php';
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

$tester = new createTaskTester();
$tester->login();

$task = new stdclass();
$task->name     = '开发任务';
$task->estimate = '2';
$task->desc     = '开发任务的描述';

$task1 = new stdclass();
$task1->name     = '设计任务';
$task1->estimate = '1';
$task1->desc     = '设计任务的描述';

$task2 = new stdclass();
$task2->name = '';

$task3 = new stdclass();
$task3->name     = '事务任务';
$task3->estimate = '1';
$task3->desc     = '事务任务的描述';

$task4 = new stdclass();
$task4->name = '测试任务';

$task5 = new stdclass();
$task5->name = '多人串行任务';
$task5->teamEstimate1 = '2';
$task5->teamEstimate2 = '1';
$task5->teamEstimate3 = '1';
$task5->desc = '多人串行任务的描述';

$task6 = new stdclass();
$task6->name = '多人并行任务';
$task6->teamEstimate1 = '1.5';
$task6->teamEstimate2 = '0.5';
$task6->teamEstimate3 = '21.5';
$task6->desc = '多人并行任务的描述';

r($tester->createDevTask($task)) && p('message') && e('成功创建开发任务');           // 创建开发任务
r($tester->createDesignTask($task1)) && p('message') && e('成功创建设计任务');       // 创建设计任务
r($tester->createNameBlankTask($task2)) && p('message') && e('任务名称不能为空');    // 创建名称为空的任务
r($tester->createAffairTask($task3)) && p('message') && e('成功创建事务任务');       // 创建事务任务
r($tester->createTestTask($task4))  && p('message') && e('成功创建测试任务');        // 创建测试任务
r($tester->createLinearTask($task5)) && p('message') && e('成功创建多人串行任务');   // 创建多人串行任务
r($tester->createMultiTask($task6)) && p('message') && e('成功创建多人并行任务');    // 创建多人并行任务

$tester->closeBrowser();
