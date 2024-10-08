#!/usr/bin/env php
<?php

/**
title=关联需求
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/linkstory.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-3');
$project->project->range('0, 1, 1');
$project->model->range('scrum, []{2}');
$project->type->range('project, sprint{2}');
$project->auth->range('extend, []{2}');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`');
$project->grade->range('1');
$project->name->range('项目1, 执行1, 执行2-无产品');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-2');
$projectProduct->product->range('1');
$projectProduct->gen(2);

$story = zenData('story');
$story->id->range('1-7');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-7');
$story->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,7,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->title->range('业需1, 用需1, 研需1, 研需2, 研需3, 研需4, 研需5');
$story->type->range('epic, requirement, story{5}');
$story->estimate->range('0');
$story->status->range('active{3}, closed, reviewing, draft, changing');
$story->stage->range('wait{3}, closed, wait{3}');
$story->version->range('1');
$story->gen(7);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-7');
$storySpec->version->range('1');
$storySpec->title->range('业需1, 用需1, 研需1, 研需2, 研需3, 研需4, 研需5');
$storySpec->spec->range('[]');
$storySpec->gen(7);

$projectStory = zenData('projectstory');
$projectStory->gen(0);

$tester = new linkStoryTester();
$tester->login();

r($tester->checkNoProductInfo()) && p('status,message') && e('SUCCESS,执行未关联产品时提示正确'); //执行未关联产品时点击关联需求
r($tester->linkstory())          && p('status,message') && e('SUCCESS,关联需求成功');             //正常关联需求
$tester->closeBrowser();
