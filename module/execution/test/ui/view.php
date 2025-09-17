#!/usr/bin/env php
<?php

/**
title=概况页面
timeout=0
cid=1

- 执行tester模块的checkBasic方法，参数是$basic▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @执行基础信息正确
- 执行tester模块的checkProduct方法，参数是'产品1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @产品信息正确
- 执行tester模块的checkMember方法，参数是'admin', 'USER1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @团队成员信息正确
- 执行tester模块的checkDoclib方法，参数是'执行库1', '执行库2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @文档库信息正确

 */

chdir(__DIR__);
include '../lib/ui/view.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 0, 2');
$project->model->range('[], scrum, []');
$project->type->range('program, project, sprint');
$project->auth->range('[], extend, []');
$project->storyType->range('[], story, []');
$project->parent->range('0, 1, 2');
$project->path->range('`,1,`, `,1,2,`, `,1,2,3`');
$project->grade->range('1, 2, 1');
$project->name->range('项目集, 项目, 执行');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing');
$project->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('2{2}, 3');
$projectProduct->product->range('1, 2, 1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(3);

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
$bug->product->range('1{8}, 2{100}');
$bug->execution->range('3{5}, 0{100}');
$bug->title->range('1-100');
$bug->status->range('active{3}, resolved{3}, closed{100}');
$bug->assignedTo->range('[]');
$bug->gen(12);

$doclib = zenData('doclib');
$doclib->id->range('1-100');
$doclib->type->range('project, execution{2}');
$doclib->product->range('0');
$doclib->project->range('2');
$doclib->execution->range('0, 3, 3');
$doclib->name->range('项目库, 执行库1, 执行库2');
$doclib->gen(3);

$tester = new viewTester();
$tester->login();

$basic = array(
    'executionName' => '执行',
    'programName'   => '项目集',
    'projectName'   => '项目',
    'storyNum'      => '2',
    'taskNum'       => '11',
    'bugNum'        => '5',
);

r($tester->checkBasic($basic))                && p('status,message') && e('SUCCESS,执行基础信息正确');
r($tester->checkProduct('产品1'))             && p('status,message') && e('SUCCESS,产品信息正确');
r($tester->checkMember('admin', 'USER1'))     && p('status,message') && e('SUCCESS,团队成员信息正确');
r($tester->checkDoclib('执行库1', '执行库2')) && p('status,message') && e('SUCCESS,文档库信息正确');
$tester->closeBrowser();
