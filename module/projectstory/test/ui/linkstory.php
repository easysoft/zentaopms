#!/usr/bin/env php
<?php

/**

title=关联需求
timeout=0
cid=1

- 正常关联需求
 - 最终测试状态 @SUCCESS
 - 测试结果 @关联需求成功

 */

chdir(__DIR__);
include '../lib/linkstory.ui.class.php';

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
$story->stage->range('wait');
$story->assignedTo->range('[]');
