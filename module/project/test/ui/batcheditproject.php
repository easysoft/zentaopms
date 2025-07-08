#!/usr/bin/env php
<?php

/**

title=批量编辑项目
timeout=0
cid=23

- 批量编辑项目缺少项目名称
 - 测试结果 @项目名称必填提示信息正确
 - 最终测试状态 @SUCCESS
- 批量编辑项目计划完成时间小于计划开始时间
 - 测试结果 @计划完成校验提示信息正确
 - 最终测试状态 @SUCCESS
- 批量编辑项目名称为已有名称
 - 测试结果 @项目名称唯一提示信息正确
 - 最终测试状态 @SUCCESS
- 批量编辑项目名称
 - 测试结果 @批量编辑项目成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/batcheditproject.ui.class.php';

$project = zenData('project');
$project->id->range('1-2');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->auth->range('extend');
$project->storyType->range('story');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('敏捷项目1, 敏捷项目2');
$project->hasProduct->range('1');
$project->status->range('doing');
$project->begin->range('(-2w)-(-1w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2w)-(+3w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->vision->range('rnd');
$project->gen(2);

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1, 产品2');
$product->shadow->range('0');
$product->type->range('normal');
$product->status->range('normal');
