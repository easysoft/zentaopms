#!/usr/bin/env php
<?php

/**
title=检查地盘贡献数据
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/contribute.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-2');
$project->project->range('0,1');
$project->model->range('scrum,[]');
$project->type->range('project,sprint');
$project->parent->range('0,1');
$project->auth->range('extend');
$project->storytype->range('story');
$project->path->range('`,1,`,`,1,2,`');
$project->grade->range('1');
$project->name->range('项目1,迭代1');
$project->hasProduct->range('1');
$project->status->range('doing');
$project->acl->range('open');
$project->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-2');
$projectProduct->product->range('1');
$projectProduct->gen(2);

$task = zenData('task');
$task->id->range('1-10');
$task->parent->range('0');
$task->project->range('1');
$task->execution->range('2');
$task->story->range('0');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10');
$task->status->range('closed{2},cancel{1},wait{5},done{2}');
$task->closedBy->range('admin{2},{8}');
$task->canceledBy->range('null{2},admin{1},{7}');
$task->assignedTo->range('closed{2},admin{4}');
$task->finishedBy->range('admin{2},{8}');
$task->gen(10);

$story = zenData('story');
$story->id->range('1-27');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-27');
$story->path->range('1-27');
$story->grade->range('1');
$story->product->range('1');
$story->version->range('1');
$story->title->range('研需01,研需02,研需03,研需04,研需05,研需06,研需07,研需08,研需09,研需10,用需01,用需02,用需03,用需04,用需05,用需06,用需07,业需01,业需02,业需03,业需04,业需05,业需06,业需07,业需08,业需09,业需10');
$story->type->range('story{10},requirement{7},epic{10}');
$story->status->range('active{3},closed{3},reviewing{4},active{3},closed{2},reviewing{6},closed{2},reviewing{4}');
$story->openedBy->range('admin');
$story->assignedTo->range('admin{3},closed{3},admin{2},{5},closed{2},admin{12}');
$story->closedBy->range('{3},admin{3},{7},admin{2},{6},admin{2},{4}');
$story->reviewedBy->range('admin{3},{7},admin{3},{8},admin{2},{4}');
$story->deleted->range('0');
$story->gen(27);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-27');
$storyspec->version->range('1');
$storyspec->title->range('研需01,研需02,研需03,研需04,研需05,研需06,研需07,研需08,研需09,研需10,用需01,用需02,用需03,用需04,用需05,用需06,用需07,业需01,业需02,业需03,业需04,业需05,业需06,业需07,业需08,业需09,业需10');
$storyspec->gen(27);
$bug->id->range('1-7');
$bug->product->range('1');
$bug->project->range('1');
$bug->execution->range('2');
$bug->module->range('0');
$bug->plan->range('0');
$bug->story->range('0');
$bug->storyVersion->range('0');
$bug->openedBuild->range('trunk');
$bug->title->range('bug1,bug2,bug3,bug4,bug5,bug6,bug7');
$bug->status->range('closed{2},resolved,active{4}');
$bug->assignedTo->range('closed{2},admin{3},{2}');
$bug->resolvedBy->range('admin{3},{4}');
$bug->resolvedBuild->range('trunk{3},{4}');
$bug->resolution->range('fixed{3},{4}');
$bug->closedBy->range('admin{2},{5}');
$bug->gen(7);
