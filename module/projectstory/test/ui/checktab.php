#!/usr/bin/env php
<?php

/**

title=项目下需求列表标签切换检查
timeout=0
cid=1

- 检查全部标签下显示条数
 - 测试结果 @allTab下显示条数正确
 - 最终测试状态 @SUCCESS
- 检查未关闭标签下显示条数
 - 测试结果 @unclosedTab下显示条数正确
 - 最终测试状态 @SUCCESS
- 检查草稿标签下显示条数
 - 测试结果 @draftTab下显示条数正确
 - 最终测试状态 @SUCCESS
- 检查评审中标签下显示条数
 - 测试结果 @reviewingTab下显示条数正确
 - 最终测试状态 @SUCCESS
- 检查变更中标签下显示条数
 - 测试结果 @changingTab下显示条数正确
 - 最终测试状态 @SUCCESS
- 检查已关闭标签下显示条数
 - 测试结果 @closedTab下显示条数正确
 - 最终测试状态 @SUCCESS
- 检查已关联执行标签下显示条数
 - 测试结果 @linkedExecutionTab下显示条数正确
 - 最终测试状态 @SUCCESS
- 检查未关联执行标签下显示条数
 - 测试结果 @unlinkedExecutionTab下显示条数正确
 - 最终测试状态 @SUCCESS

*/

chdir(__DIR__);
include '../lib/ui/checktab.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-2');
$project->project->range('0,1');
$project->model->range('scrum');
$project->type->range('project,sprint');
$project->auth->range('extend,[]');
$project->storytype->range('`story,epic,requirement`,`story`');
$project->path->range('`,1,`, `,1,2,`');
$project->grade->range('1');
$project->name->range('项目1,执行1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('1');
$projectProduct->gen(1);

$story = zenData('story');
$story->id->range('1-10');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-10');
$story->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,7,`, `,8,`, `,9,`, `,10,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->title->range('需求001,需求002,需求003,需求004,需求005,需求006,需求007,需求008,需求009,需求010');
$story->type->range('story');
$story->estimate->range('0');
$story->status->range('active{3}, closed{1}, reviewing{2}, draft{1}, changing{3}');
$story->stage->range('projected');
$story->assignedTo->range('[]');
$story->version->range('1');
$story->gen(10);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-10');
$storySpec->version->range('1');
$storySpec->title->range('1-10');
$storySpec->gen(10);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{10},2{1}');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-10,3');
$projectStory->version->range('1');
$projectStory->order->range('1{10},2{1}');
$projectStory->gen(11);

$tester = new checkTabTester();
$tester->login();

/* 标签统计 */
r($tester->checkTab('allTab', '10'))              && p('message,status') && e('allTab下显示条数正确,SUCCESS');               //检查全部标签下显示条数
r($tester->checkTab('unclosedTab', '9'))          && p('message,status') && e('unclosedTab下显示条数正确,SUCCESS');          //检查未关闭标签下显示条数
r($tester->checkTab('draftTab', '1'))             && p('message,status') && e('draftTab下显示条数正确,SUCCESS');             //检查草稿标签下显示条数
r($tester->checkTab('reviewingTab', '2'))         && p('message,status') && e('reviewingTab下显示条数正确,SUCCESS');         //检查评审中标签下显示条数
r($tester->checkTab('changingTab', '3'))          && p('message,status') && e('changingTab下显示条数正确,SUCCESS');          //检查变更中标签下显示条数
r($tester->checkTab('closedTab', '1'))            && p('message,status') && e('closedTab下显示条数正确,SUCCESS');            //检查已关闭标签下显示条数
r($tester->checkTab('linkedExecutionTab', '1'))   && p('message,status') && e('linkedExecutionTab下显示条数正确,SUCCESS');   //检查已关联执行标签下显示条数
r($tester->checkTab('unlinkedExecutionTab', '9')) && p('message,status') && e('unlinkedExecutionTab下显示条数正确,SUCCESS'); //检查未关联执行标签下显示条数

$tester->closeBrowser();
