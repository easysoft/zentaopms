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
include '../lib/export.ui.class.php';

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
