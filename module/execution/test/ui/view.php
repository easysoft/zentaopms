#!/usr/bin/env php
<?php

/**
title=概况页面
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/view.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1,产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 0, 1');
$project->model->range('[], scrum, []');
$project->type->range('program, project, sprint');
$project->auth->range('[], extend, []');
$project->storyType->range('[], story, []');
$project->parent->range('0, 1, 2');
$project->path->range('`,1,`, `,1,2,`, `,1,2,3`');
$project->grade->range('1, 2, 1');
$project->name->range('项目集, 项目, 执行');
$project->begin->range('2024-11-01')->type('timestamp')->format('YY/MM/DD');
$project->end->range('2024-11-31')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing');
$project->gen(3);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('2{2}, 3');
$projectproduct->product->range('1, 2, 1');
$projectproduct->gen(3);

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('0');
$user->account->range('admin, user1, user2');
$user->realname->range('admin, USER1, USER2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(3);

$team = zenData('team');
$team->id->range('1-100');
$team->root->range('2{3}, 3{2}');
$team->type->range('project{3}, execution{2}');
$team->account->range('admin, user1, user2, admin, user1');
$team->gen(5);

$story->id->range('1-100');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-100');
$story->path->range('`,1,`, `,2,`, `,3,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->title->range('1-100');
$story->type->range('story');
$story->estimate->range('0');
$story->status->range('active');
$story->stage->range('projected');
$story->assignedTo->range('[]');
$story->version->range('1');
$story->gen(3);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-15');
$storySpec->version->range('1');
$storySpec->title->range('1-15');
$storySpec->gen(3);

$projectStory = zenData('projectstory');
$projectStory->project->range('2{3}, 3{2}');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-3, 1, 2');
$projectStory->version->range('1');
$projectStory->gen(5);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('2');
$task->execution->range('3');
$task->story->range('0');
$task->storyVersion->range('1');
$task->name->range('1-100');
$task->type->range('devel');
$task->deadline->range(' (-5D)-(-4D):1D, []{11}')->type('timestamp')->format('YY/MM/DD');
$task->status->range('wait{3}, doing{3}, done{2}, cancel, closed{3}');
$task->openedBy->range('admin{6}, user1{6}');
$task->assignedTo->range('[]{3}, user1{4}, admin{2}, closed{3}');
$task->finishedBy->range('[]{6}, admin, user1, [], admin, user1{2}');
$task->canceledBy->range('[]{8}, admin, []{3}');
$task->closedBy->range('[]{9}, admin{2}, user1');
$task->deleted->range('0{11}, 1');
$task->gen(12);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('0');
$taskSpec->name->range('1-100');
$taskSpec->gen(12);

$bug = zenData('bug');
$bug->id->range('1-100');
$bug->project->range('2{12}, 0{100}');
$bug->product->range('1{8},2{100}');
$bug->execution->range('2{5}, 0{100}');
$bug->title->range('1-100');
$bug->status->range('active{5}, resolved{3}, closed{100}');
$bug->assignedTo->range('[]');
$bug->gen(12);
