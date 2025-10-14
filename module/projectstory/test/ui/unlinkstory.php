#!/usr/bin/env php
<?php

/**

title=项目移除和批量移除研发需求
timeout=0
cid=73

- 单个移除研发需求
 - 测试结果 @单个移除需求成功
 - 最终测试状态 @SUCCESS
- 批量移除研发需求
 - 测试结果 @移除全部需求成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/unlinkstory.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->auth->range('extend');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(1);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('1');
$projectProduct->gen(1);

$story = zenData('story');
$story->id->range('1-5');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-5');
$story->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->title->range('需求001,需求002,需求003,需求004,需求005');
$story->type->range('story');
$story->estimate->range('0');
$story->status->range('active{2}, reviewing{1}, draft{1}, changing{1}');
$story->stage->range('projected');
$story->assignedTo->range('[]');
$story->version->range('1');
$story->gen(5);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-5');
$storySpec->version->range('1');
$storySpec->title->range('1-5');
$storySpec->gen(5);

$projectStory = zenData('projectstory');
$projectStory->project->range('1');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-5');
$projectStory->version->range('1');
$projectStory->order->range('1');
$projectStory->gen(5);

$tester = new unLinkStoryTester();
$tester->login();

r($tester->unlinkStory())      && p('message,status') && e('单个移除需求成功,SUCCESS');  // 单个移除研发需求
r($tester->batchUnlinkStory()) && p('message,status') && e('移除全部需求成功,SUCCESS');  // 批量移除研发需求

$tester->closeBrowser();
