#!/usr/bin/env php
<?php

/**

title=项目下导出Bug操作检查
timeout=0
cid=1

- 按照默认设置导出
 - 最终测试状态 @SUCCESS
 - 测试结果 @导出Bug成功
- 导出xml选中记录
 - 最终测试状态 @SUCCESS
 - 测试结果 @导出Bug成功
- 导出html全部记录
 - 最终测试状态 @SUCCESS
 - 测试结果 @导出Bug成功

*/

chdir(__DIR__);
include '../lib/bug.ui.class.php';

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
