#!/usr/bin/env php
<?php

/**
title=检查运营界面目标列表各tab下数据
timeout=0
cid=0

- 检查全部Tab
 - 测试结果 @全部Tab下数据显示正确
 - 最终测试状态 @SUCCESS
- 检查未关闭Tab
 - 测试结果 @未关闭Tab下数据显示正确
 - 最终测试状态 @SUCCESS
- 检查草稿Tab
 - 测试结果 @草稿Tab下数据显示正确
 - 最终测试状态 @SUCCESS
- 检查评审中Tab
 - 测试结果 @评审中Tab下数据显示正确
 - 最终测试状态 @SUCCESS
- 检查变更中Tab
 - 测试结果 @变更中Tab下数据显示正确
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/switchtabinlite.ui.class.php';

$project = zenData('project');
$project->id->range('1');
$project->model->range('kanban');
$project->type->range('project');
$project->name->range('运营项目');
$project->hasProduct->range('0');
$project->acl->range('open');
$project->vision->range('lite');
$project->gen(1);

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('运营项目');
$product->shadow->range('1');
$product->type->range('normal');
$product->acl->range('open');
$product->vision->range('lite');
$product->gen(1);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1');
$projectproduct->product->range('1');
$projectproduct->branch->range('0');
$projectproduct->plan->range('0');
$projectproduct->gen(1);

$team = ZenData('team');
$team->id->range('1');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin');
$team->gen(1);

$story = ZenData('story');
$story->id->range('1-12');
$story->vision->range('lite');
$story->parent->range('0');
$story->isparent->range('0');
$story->root->range('1-12');
$story->path->range('1-12');
$story->product->range('1');
$story->title->range('目标01,目标02,目标03,目标04,目标05,目标06,目标07,目标08,目标09,目标10,目标11,目标12');
$story->type->range('story');
$story->status->range('closed{2},active,changing,reviewing{6},draft{2}');
$story->stage->range('closed{2},projected{10}');
$story->version->range('1');
$story->openedBy->range('admin');
$story->assignedTo->range('closed{2},{10}');
$story->changedBy->range('{3},admin,{8}');
$story->reviewedBy->range('admin{3},{9}');
$story->closedBy->range('admin{2},{10}');
$story->closedReason->range('done{2},{10}');
$story->deleted->range('0');
$story->gen(12);

$storyspec = ZenData('storyspec');
$storyspec->story->range('1-12');
$storyspec->version->range('1');
$storyspec->title->range('目标01,目标02,目标03,目标04,目标05,目标06,目标07,目标08,目标09,目标10,目标11,目标12');
$storyspec->gen(12);

$storyreview = ZenData('storyreview');
$storyreview->story->range('1-12');
$storyreview->version->range('1');
$storyreview->reviewer->range('admin');
$storyreview->result->range('pass{4},{8}');
$storyreview->gen(12);

$projectstory = ZenData('projectstory');
$projectstory->project->range('1');
$projectstory->product->range('1');
$projectstory->branch->range('0');
$projectstory->story->range('1-12');
$projectstory->version->range('1');
$projectstory->order->range('1-12');
$projectstory->gen(12);

$action = zenData('action');
$action->id->range('1-37');
$action->objectType->range('product,project,story{35}');
$action->objectID->range('1{3},2,3,4,5,6,7,8,9,10,1,2,3,4,5,6,7,8,9,10,11,12,11,12,1{2},2{2},3{2},4{3},1,2');
$action->product->range('1');
$action->project->range('0,1,0{10},1{10},0{2},1{13}');
$action->execution->range('0');
$action->actor->range('admin{27},系统,admin,系统,admin,系统,admin,系统,admin{3}');
$action->action->range('opened{12},linked2project{10},opened{2},linked2project{2},reviewed,reviewpassed,reviewed,reviewpassed,reviewed,reviewpassed,reviewed,reviewpassed,changed,closed{2}');
$action->extra->range('{12},1{10},{2},1{2},Pass,pass|draft,Pass,pass|draft,Pass,pass|draft,Pass,pass|draft,{1},Done|active{2}');
$action->vision->range('lite');
$action->gen(37);

$projectID = array('projectID' => '1');
$tester = new switchTab();
$tester->login();

r($tester->switchTab($projectID, 'allTab', '12'))      && p('message,status') && e('全部Tab下数据显示正确,SUCCESS');//检查全部Tab
r($tester->switchTab($projectID, 'openTab', '10'))     && p('message,status') && e('未关闭Tab下数据显示正确,SUCCESS');//检查未关闭Tab
r($tester->switchTab($projectID, 'draftTab', '2'))     && p('message,status') && e('草稿Tab下数据显示正确,SUCCESS');//检查草稿Tab
r($tester->switchTab($projectID, 'reviewingTab', '6')) && p('message,status') && e('评审中Tab下数据显示正确,SUCCESS');//检查评审中Tab
r($tester->switchTab($projectID, 'changingTab', '1'))  && p('message,status') && e('变更中Tab下数据显示正确,SUCCESS');//检查变更中Tab

$tester->closeBrowser();
