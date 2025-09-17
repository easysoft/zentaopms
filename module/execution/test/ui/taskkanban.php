#!/usr/bin/env php
<?php

/**
title=任务看板
timeout=0
cid=1

- 执行tester模块的checkKanban方法，参数是'1', '1', '2', '3', '1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'1', '4', '4', '4', '5'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'1', '1', '1', '4', '3'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确

 */

chdir(__DIR__);
include '../lib/ui/taskkanban.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1');
$project->model->range('scrum, []');
$project->type->range('project, sprint');
$project->auth->range('extend, []');
$project->storyType->range('story, []');
$project->parent->range('0, 1');
$project->path->range('`,1,`, `,1,2,`');
$project->grade->range('1');
$project->name->range('项目, 执行');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing');
$project->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1, 2');
$projectProduct->product->range('1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(2);

$story = zenData('story');
$story->id->range('1-100');
$story->parent->range('0{3}, 3{2}');
$story->isParent->range('0{2}, 1, 0{2}');
$story->root->range('1, 2, 3{3}');
$story->path->range('`,1,`, `,2,`, `,3,`, `,3,4,`, `,3,5,`');
$story->grade->range('1{3}, 2{2}');
$story->product->range('1');
$story->title->range('1-100');
$story->type->range('story');
$story->category->range('feature, interface{100}');
$story->estimate->range('0');
$story->status->range('active');
$story->stage->range('projected{2}, developing, testing{2} ');
$story->assignedTo->range('[]');
$story->version->range('1');
$story->gen(5);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-100');
$storySpec->version->range('1');
$storySpec->title->range('1-100');
$storySpec->gen(3);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{5}, 2{5}');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-5 ');
$projectStory->version->range('1');
$projectStory->order->range('1-5');
$projectStory->gen(9);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('1');
$task->execution->range('2');
$task->name->range('1-100');
$task->status->range('wait, doing{2}, done{3}, pause{4}, cancel{5}, closed{6}');
$task->gen(21);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('0');
$taskSpec->name->range('1-100');
$taskSpec->gen(21);

$bug = zenData('bug');
$bug->id->range('1-100');
$bug->project->range('1{6}, 0{100}');
$bug->product->range('1{8}');
$bug->execution->range('2{5}, 0{100}');
$bug->title->range('1-100');
$bug->status->range('active{3}, resolved{3}, closed{100}');
$bug->assignedTo->range('[]');
$bug->gen(8);

$tester = new taskkanbanTester();
$tester->login();

r($tester->checkKanban('1', '1', '2', '3', '1')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('1', '4', '4', '4', '5')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('1', '1', '1', '4', '3')) && p('status,message') && e('SUCCESS,数据正确');
$tester->closeBrowser();
