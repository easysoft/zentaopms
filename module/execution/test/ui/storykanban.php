#!/usr/bin/env php
<?php

/**
title=需求看板
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/storykanban.ui.class.php';

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
$story->parent->range('0{3}, 3{2}, 0{100}');
$story->isParent->range('0{2}, 1, 0{100}');
$story->root->range('1, 2, 3{3}, 6-100');
$story->path->range('`,1,`, `,2,`, `,3,`, `,3,4,`, `,3,5,`, `,6,`, `,7,`, `,8,`, `,9,`, `,10,`');
$story->grade->range('1{3}, 2{2}, 1{100}');
$story->product->range('1');
$story->title->range('1-100');
$story->type->range('story');
$story->estimate->range('0');
$story->status->range('active');
$story->stage->range('projected{2}, developing, testing{2}, developed, tested, verified{2}, released');
$story->assignedTo->range('[]');
$story->version->range('1');
$story->gen(10);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-100');
$storySpec->version->range('1');
$storySpec->title->range('1-100');
$storySpec->gen(10);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{10}, 2{10}');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-10 ');
$projectStory->version->range('1');
$projectStory->order->range('1-10');
$projectStory->gen(20);

$tester = new storykanbanTester();
$tester->login();

r($tester->check('projected', '2'))  && p('status,message') && e('SUCCESS,projected列数据有误');
r($tester->check('developing', '1')) && p('status,message') && e('SUCCESS,developing列数据有误');
r($tester->check('developed', '1'))  && p('status,message') && e('SUCCESS,developed列数据有误');
r($tester->check('testing', '2'))    && p('status,message') && e('SUCCESS,testing列数据有误');
r($tester->check('tested', '1'))     && p('status,message') && e('SUCCESS,tested列数据有误');
r($tester->check('accepted', '2'))   && p('status,message') && e('SUCCESS,accepted列数据有误');
r($tester->check('released', '1'))   && p('status,message') && e('SUCCESS,released列数据有误');
$tester->closeBrowser();
