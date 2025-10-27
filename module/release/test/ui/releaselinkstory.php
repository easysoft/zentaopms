#!/usr/bin/env php
<?php

/**

title=项目发布关联和移除研发需求
timeout=0
cid=73

- 项目发布关联研发需求
 - 测试结果 @发布关联需求成功
 - 最终测试状态 @SUCCESS
- 单个移除研发需求
 - 测试结果 @单个移除需求成功
 - 最终测试状态 @SUCCESS
- 批量移除研发需求
 - 测试结果 @批量移除需求成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/releaselinkstory.ui.class.php';

$product = zenData('product');
$product->gen(0);

$release = zenData('release');
$release->gen(0);

$build = zenData('build');
$build->gen(0);

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$system = zenData('system');
$system->id->range('1');
$system->product->range('1');
$system->name->range('应用AAA');
$system->status->range('active');
$system->integrated->range('0');
$system->createdBy->range('admin');
$system->gen(1);

$release = zenData('release');
$release->id->range('1');
$release->product->range('1');
$release->branch->range('0');
$release->name->range('发布1');
$release->system->range('1');
$release->status->range('wait');
$release->stories->range('[]');
$release->bugs->range('[]');
$release->desc->range('描述111');
$release->deleted->range('0');
$release->gen(1);

$story = zenData('story');
$story->id->range('1-5');
$story->path->range('[]');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->source->range('[]');
$story->title->range('需求001,需求002,需求003,需求004,需求005');
$story->type->range('story');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(5);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-5');
$storyspec->version->range('1');
$storyspec->title->range('需求001,需求002,需求003,需求004,需求005');
$storyspec->gen(5);

$tester = new releaseLinkStoryTester();
$tester->login();

r($tester->linkStory())        && p('message,status') && e('发布关联需求成功,SUCCESS'); // 项目发布关联研发需求
r($tester->unlinkStory())      && p('message,status') && e('单个移除需求成功,SUCCESS'); // 单个移除研发需求
r($tester->batchUnlinkStory()) && p('message,status') && e('批量移除需求成功,SUCCESS'); // 批量移除研发需求

$tester->closeBrowser();
