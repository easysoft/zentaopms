#!/usr/bin/env php
<?php

/**
title=执行下需求列表操作检查
timeout=0
cid=1

- 检查全部标签下显示条数
 - 最终测试状态 @SUCCESS
 - 测试结果 @allTab下显示条数正确
- 检查未关闭标签下显示条数
 - 最终测试状态 @SUCCESS
 - 测试结果 @unclosedTab下显示条数正确
- 检查草稿标签下显示条数
 - 最终测试状态 @SUCCESS
 - 测试结果 @draftTab下显示条数正确
- 检查评审中标签下显示条数
 - 最终测试状态 @SUCCESS
 - 测试结果 @reviewingTab下显示条数正确
- 移除需求
 - 最终测试状态 @SUCCESS
 - 测试结果 @需求移除成功
- 批量移除需求
 - 最终测试状态 @SUCCESS
 - 测试结果 @需求批量移除成功
- 编辑草稿状态的需求的阶段为测试中
 - 最终测试状态 @SUCCESS
 - 测试结果 @批量编辑draft阶段成功
- 编辑评审中状态的需求的阶段为未开始
 - 最终测试状态 @SUCCESS
 - 测试结果 @批量编辑reviewing阶段成功
- 编辑激活状态的需求的阶段为已验收
 - 最终测试状态 @SUCCESS
 - 测试结果 @批量编辑active阶段成功
- 编辑变更中状态的需求的阶段为已计划
 - 最终测试状态 @SUCCESS
 - 测试结果 @批量编辑changing阶段成功
- 编辑已关闭状态的需求的阶段为验收失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @批量编辑closed阶段成功
- 单个指派
 - 最终测试状态 @SUCCESS
 - 测试结果 @指派成功
- 批量指派
 - 最终测试状态 @SUCCESS
 - 测试结果 @批量指派成功

 */

chdir(__DIR__);
include '../lib/ui/story.ui.class.php';
global $config;

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0, 1, 1, 0, 4');
$project->model->range('scrum, []{2}, scrum, []');
$project->type->range('project, sprint{2}, project, sprint');
$project->auth->range('extend, []{2}, extend, []');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,4,`, `,4,5,`');
$project->grade->range('1');
$project->name->range('项目1, 项目1执行1, 项目1执行2, 项目2, 项目2执行1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-5');
$projectProduct->product->range('1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(5);

$story = zenData('story');
$story->id->range('1-100');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-15');
$story->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,7,`, `,8,`, `,9,`, `,10,`, `,11,`, `,12,`, `,13,`, `,14,`, `,15,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->title->range('1-15');
$story->type->range('story');
$story->estimate->range('0');
$story->status->range('active{3}, closed{3}, reviewing{3}, draft{3}, changing{3}');
$story->stage->range('projected');
$story->assignedTo->range('[]');
$story->version->range('1');
$story->gen(15);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-15');
$storySpec->version->range('1');
$storySpec->title->range('1-15');
$storySpec->gen(15);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{15}, 2{15}');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-15, 1-15');
$projectStory->version->range('1');
$projectStory->order->range('1{15}, 2{15}');
$projectStory->gen(30);

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('0');
$user->account->range('admin, user1, user2');
$user->realname->range('admin, USER1, USER2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(3);

$team = zenData('team');
$team->id->range('1-100');
$team->root->range('1{3}, 2{3}');
$team->type->range('project{3}, execution{3}');
$team->account->range('admin, user1, user2, admin, user1, user2');
$team->gen(6);


$tester = new storyTester();
$tester->login();
$tester->open();

/* 标签统计 */
r($tester->checkTab('allTab', '15'))      && p('status,message') && e('SUCCESS,allTab下显示条数正确');       //检查全部标签下显示条数
r($tester->checkTab('unclosedTab', '12')) && p('status,message') && e('SUCCESS,unclosedTab下显示条数正确');  //检查未关闭标签下显示条数
r($tester->checkTab('draftTab', '3'))     && p('status,message') && e('SUCCESS,draftTab下显示条数正确');     //检查草稿标签下显示条数
r($tester->checkTab('reviewingTab', '3')) && p('status,message') && e('SUCCESS,reviewingTab下显示条数正确'); //检查评审中标签下显示条数
/* 移除需求 */
r($tester->unlinkStory())       && p('status,message') && e('SUCCESS,需求移除成功');     //移除需求
r($tester->batchUnlinkStory())  && p('status,message') && e('SUCCESS,需求批量移除成功'); //批量移除需求
/* 批量编辑阶段 */
r($tester->batchEditPhase('draft', 'testing'))    && p('status,message') && e('SUCCESS,批量编辑draft阶段成功');     //编辑草稿状态的需求的阶段为测试中
r($tester->batchEditPhase('reviewing', 'wait'))   && p('status,message') && e('SUCCESS,批量编辑reviewing阶段成功'); //编辑评审中状态的需求的阶段为未开始
r($tester->batchEditPhase('active', 'verified'))  && p('status,message') && e('SUCCESS,批量编辑active阶段成功');    //编辑激活状态的需求的阶段为已验收
r($tester->batchEditPhase('changing', 'planned')) && p('status,message') && e('SUCCESS,批量编辑changing阶段成功');  //编辑变更中状态的需求的阶段为已计划
r($tester->batchEditPhase('closed', 'rejected'))  && p('status,message') && e('SUCCESS,批量编辑closed阶段成功');    //编辑已关闭状态的需求的阶段为验收失败
/* 指派 */
r($tester->assignTo('USER1'))  && p('status,message') && e('SUCCESS,指派成功');     //单个指派
r($tester->batchAssignTo())    && p('status,message') && e('SUCCESS,批量指派成功'); //批量指派
$tester->closeBrowser();
