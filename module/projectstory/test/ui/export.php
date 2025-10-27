#!/usr/bin/env php
<?php

/**

title=导出项目需求
timeout=0
cid=73

- 按照默认设置导出项目需求
 - 测试结果 @项目需求导出成功
 - 最终测试状态 @SUCCESS
- 项目需求导出csv-UTF-8-选中记录
 - 测试结果 @项目需求导出成功
 - 最终测试状态 @SUCCESS
- 项目需求导出xml-全部记录
 - 测试结果 @项目需求导出成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/export.ui.class.php';

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
$projectStory->project->range('1');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-10');
$projectStory->version->range('1');
$projectStory->order->range('1');
$projectStory->gen(10);

$tester = new exportTester();
$tester->login();

//设置项目需求导出数据
$projectStory = array(
    array('filename' => ''),
    array('filename' => '项目导出文件1', 'encoding' => 'UTF-8', 'data' => '选中记录'),
    array('filename' => '项目导出文件2', 'format' => 'xml'),
);

r($tester->export($projectStory['0'])) && p('message,status') && e('项目需求导出成功,SUCCESS');   // 按照默认设置导出项目需求
r($tester->export($projectStory['1'])) && p('message,status') && e('项目需求导出成功,SUCCESS');   // 项目需求导出csv-UTF-8-选中记录
r($tester->export($projectStory['2'])) && p('message,status') && e('项目需求导出成功,SUCCESS');   // 项目需求导出xml-全部记录

$tester->closeBrowser();
