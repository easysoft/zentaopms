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
