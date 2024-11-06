#!/usr/bin/env php
<?php

/**
title=需求看板
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/storykanban.ui.class.php';

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

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1, 2');
$projectproduct->product->range('1');
$projectproduct->gen(2);

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
$projectStory->gen(10);

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
