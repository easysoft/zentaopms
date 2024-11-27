#!/usr/bin/env php
<?php

/**

title=项目需求指派和批量指派需求
timeout=0
cid=1

- 单个指派
 - 最终测试状态 @SUCCESS
 - 测试结果 @指派成功
- 批量指派
 - 最终测试状态 @SUCCESS
 - 测试结果 @批量指派成功

*/

chdir(__DIR__);
include '../lib/assign.ui.class.php';
global $config;

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
