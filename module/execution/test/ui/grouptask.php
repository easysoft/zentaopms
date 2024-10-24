#!/usr/bin/env php
<?php

/**
title=执行下的分组视图
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/grouptask.ui.class.php';
global $config;

$user = zenData('user');
$user->id->range('1-100');
$user->account->range('admin, user1, user2, user3, user4, user5');
$user->realname->range('admin, USER1, USER2, USER3, USER4, USER5');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(6);

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
$story->gen(2);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-100');
$storySpec->version->range('1');
$storySpec->title->range('1-100');
$storySpec->gen(2);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{2}, 2');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1, 2, 1');
$projectStory->version->range('1');
$projectStory->order->range('1, 2, 1');
$projectStory->gen(3);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('1');
$task->execution->range('2');
$task->story->range('1{5}, 0{99}');
$task->storyVersion->range('1');
$task->name->range('1-100');
$task->type->range('devel');
$task->estimate->range('1');
$task->consumed->range('0{3}, 1-100');
$task->left->range('1{3}, 2{3}, 0{2}, 2{100}');
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
