#!/usr/bin/env php
<?php

/**
title=需求估算
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/storyestimate.ui.class.php';

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
$story->gen(2);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-15');
$storySpec->version->range('1');
$storySpec->title->range('1-15');
$storySpec->gen(15);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{2}, 2, 3');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-2, 1-2');
$projectStory->version->range('1');
$projectStory->order->range('1-2, 1{2}');
$projectStory->gen(4);

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('0');
$user->account->range('admin, user1, user2');
$user->realname->range('admin, USER1, USER2');
$user->password->range('77839ef72f7b71a3815a77d038e267e0');
$user->gen(3);

$team = zenData('team');
$team->id->range('1-100');
$team->root->range('1{3}, 2{3}');
$team->type->range('project{3}, execution{3}');
$team->account->range('admin, user1, user2, admin, user1, user2');
$team->gen(6);

$storyEstimate = zenData('storyestimate');
$storyEstimate->gen(0);

$estimate = array(
    array('1', '2', '3', 'averge' => '2'),
    array('1', '0', '0', 'averge' => '1'),
    array('a'),
    array('-1'),
);

$tester = new storyEstimateTester();
$tester->login();

r($tester->storyEstimate($estimate[0], '1'))          && p('status,message') && e('SUCCESS,估算成功');
r($tester->storyEstimate($estimate[1], '2'))          && p('status,message') && e('SUCCESS,估算成功');
r($tester->checkErrorInfo($estimate[2], 'notNumber')) && p('status,message') && e('SUCCESS,估算值为非数字提示成功');
r($tester->checkErrorInfo($estimate[3], 'negative'))  && p('status,message') && e('SUCCESS,估算值为负数提示成功');
r($tester->noTeamInfo())                              && p('status,message') && e('SUCCESS,没有团队成员提示成功');
$tester->closeBrowser();
