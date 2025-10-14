#!/usr/bin/env php
<?php

/**

title=项目下用例列表操作检查
timeout=0
cid=1

- 执行tester模块的checkTab方法，参数是'allTab', '4'
 - 最终测试状态 @SUCCESS
 - 测试结果 @allTab下显示用例数正确
- 执行tester模块的checkTab方法，参数是'waitingTab', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @waitingTab下显示用例数正确
- 执行tester模块的checkTab方法，参数是'storyChangedTab', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @storyChangedTab下显示用例数正确
- 执行tester模块的checkTab方法，参数是'storyNoCaseTab', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @storyNoCaseTab下显示用例数正确
- 执行tester模块的switchProduct方法，参数是'firstProduct', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @切换firstProduct查看用例数据成功
- 执行tester模块的switchProduct方法，参数是'secondProduct', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @切换secondProduct查看用例数据成功

 */

chdir(__DIR__);
include '../lib/ui/testcase.ui.class.php';

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->auth->range('extend');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('敏捷项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(1);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('1{1}, 2{1}');
$projectProduct->gen(2);

$story = zenData('story');
$story->id->range('1-4');
$story->root->range('1-4');
$story->path->range('`,1,`, `,2,`,`,3,`,`,4,`');
$story->grade->range('1');
$story->product->range('1');
$story->title->range('研发需求01,研发需求02,研发需求3,研发需求4');
$story->type->range('story');
$story->estimate->range('0');
$story->status->range('active{4}');
$story->stage->range('projected');
$story->version->range('1{2},2{2}');
$story->gen(4);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-4');
$storySpec->version->range('1');
$storySpec->title->range('1-4');
$storySpec->gen(2);

$projectStory = zenData('projectstory');
$projectStory->project->range('1');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-4');
$projectStory->version->range('1');
$projectStory->order->range('1{2},2{2}');
$projectStory->gen(4);

$case = zenData('case');
$case->id->range('1-4');
$case->project->range('1');
$case->product->range('1{2}, 2{2}');
$case->execution->range('0');
$case->story->range('1-4');
$case->storyVersion->range('1');
$case->title->range('用例1, 用例2, 用例3, 用例4');
$case->status->range('normal{2}, wait{2}');
$case->gen(4);

$projectCase = zenData('projectcase');
$projectCase->project->range('1');
$projectCase->product->range('1{2}, 2{2}');
$projectCase->case->range('1-2, 3-4');
$projectCase->gen(4);

$tester = new testcaseTester();
$tester->login();

/* 检查标签下统计数据 */
r($tester->checkTab('allTab', '4'))          && p('status,message') && e('SUCCESS,allTab下显示用例数正确');
r($tester->checkTab('waitingTab', '2'))      && p('status,message') && e('SUCCESS,waitingTab下显示用例数正确');
r($tester->checkTab('storyChangedTab', '2')) && p('status,message') && e('SUCCESS,storyChangedTab下显示用例数正确');
r($tester->checkTab('storyNoCaseTab', '2'))  && p('status,message') && e('SUCCESS,storyNoCaseTab下显示用例数正确');
/* 切换1.5级导航产品 */
r($tester->switchProduct('firstProduct', '2'))  && p('status,message') && e('SUCCESS,切换firstProduct查看用例数据成功');
r($tester->switchProduct('secondProduct', '2')) && p('status,message') && e('SUCCESS,切换secondProduct查看用例数据成功');

$tester->closeBrowser();
