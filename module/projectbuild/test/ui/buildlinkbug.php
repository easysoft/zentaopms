#!/usr/bin/env php
<?php

/**

title=项目版本关联和移除Bug
timeout=0
cid=73

- 项目版本关联bug
 - 测试结果 @版本关联bug成功
 - 最终测试状态 @SUCCESS
- 单个移除bug
 - 测试结果 @单个移除bug成功
 - 最终测试状态 @SUCCESS
- 移除全部bug
 - 测试结果 @移除全部bug成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/buildlinkbug.ui.class.php';

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
$project->attribute->range('[]');
$project->auth->range('[]');
$project->parent->range('0');
$project->grade->range('1');
$project->name->range('敏捷项目1');
$project->path->range('`,1,`');
