#!/usr/bin/env php
<?php

/**
title=树状图
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/tree.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

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
$projectproduct->project->range('1{2}, 2{2}');
$projectproduct->product->range('1, 2');
$projectproduct->gen(4);

$story = zenData('story');
$story->id->range('1-100');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-100');
$story->path->range('`,1,`, `,2,`, `,3,`');
$story->grade->range('1');
$story->product->range('1{2}, 2');
$story->module->range('1{2}, 2');
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
$storySpec->story->range('1-100');
$storySpec->version->range('1');
$storySpec->title->range('1-100');
$storySpec->gen(3);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{3}, 2{3}');
$projectStory->product->range('1{2}, 2');
$projectStory->branch->range('0');
$projectStory->story->range('1, 2, 3');
$projectStory->version->range('1');
$projectStory->order->range('1, 2, 3');
$projectStory->gen(6);

$module = zenData('module');
$module->id->range('1-100');
$module->root->range('1, 2, 2');
$module->branch->range('0');
$module->name->range('产品1模块1, 产品2模块1, 执行模块1');
$module->parent->range('0');
$module->path->range('`,1,`, `,2,`, `,3,`');
$module->grade->range('1');
$module->type->range('story{2}, task');
$module->short->range('0');
$module->gen(3);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('1');
$task->execution->range('2');
$task->module->range('1{3}, 2, 3, 0{99}');
$task->story->range('1, 1, 2, 3, 0{99}');
$task->storyVersion->range('1');
$task->name->range('1-100');
$task->gen(12);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('0');
$taskSpec->name->range('1-100');
$taskSpec->gen(12);

$tester = new treeTester();
$tester->login();
