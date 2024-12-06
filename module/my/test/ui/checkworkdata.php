#!/usr/bin/env php
<?php

/**
title=检查地盘待处理数据
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/work.ui.class.php';

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

$tester->closeBrowser();
