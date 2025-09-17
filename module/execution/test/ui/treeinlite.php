#!/usr/bin/env php
<?php

/**
title=运营界面树状图
timeout=0
cid=1

- 执行tester模块的checkTreeData方法，参数是'firstLevel', '3'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkTreeData方法，参数是'secondLevelA', '7'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkTreeData方法，参数是'secondLevelB', '2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkTreeData方法，参数是'thirdLevelB', '2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkTreeData方法，参数是'fourthLevelB', '1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkTreeData方法，参数是'firstLevel', '1', true▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkTreeData方法，参数是'secondLevelA', '2')▫▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkDetail方法▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @详情数据正确

*/

chdir(__DIR__);
include '../lib/ui/treeinlite.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('运营项目');
$product->shadow->range('1');
$product->bind->range('1');
$product->type->range('normal');
$product->vision->range('lite');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1');
$project->model->range('kanban, []');
$project->type->range('project, kanban');
$project->auth->range('[]');
$project->storyType->range('[]');
$project->parent->range('0, 1');
$project->path->range('`,1,`, `,1,2,`');
$project->grade->range('1');
$project->name->range('运营项目, 看板');
$project->hasProduct->range('0');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing');
$project->vision->range('lite');
$project->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1, 2');
$projectProduct->product->range('1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(2);

$story = zenData('story');
$story->id->range('1-100');
$story->vision->range('lite');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-100');
$story->path->range('`,1,`, `,2,`, `,3,`');
$story->grade->range('1');
$story->product->range('1');
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
$projectStory->project->range('1{3}, 2{2}');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1, 2, 3');
$projectStory->version->range('1');
$projectStory->order->range('1, 2, 3');
$projectStory->gen(5);

$module = zenData('module');
$module->id->range('1-100');
$module->root->range('1, 1, 2');
$module->branch->range('0');
$module->name->range('产品模块1, 产品模块2, 看板模块1');
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
$task->vision->range('lite');
$task->gen(12);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('0');
$taskSpec->name->range('1-100');
$taskSpec->gen(12);

$tester = new treeTester();
$tester->login();

r($tester->checkTreeData('firstLevel', '3'))       && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkTreeData('secondLevelA', '7'))     && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkTreeData('secondLevelB', '2'))     && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkTreeData('thirdLevelB', '2'))      && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkTreeData('fourthLevelB', '1'))     && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkTreeData('firstLevel', '1', true)) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkTreeData('secondLevelA', '2') )    && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkDetail())                          && p('status,message') && e('SUCCESS,详情数据正确');
$tester->closeBrowser();
