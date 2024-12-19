#!/usr/bin/env php
<?php

/**

title=编辑项目发布
timeout=0
cid=73

- 发布名称置空保存，检查提示信息测试结果 @编辑项目发布表单页提示信息正确 @SUCCESS
- 编辑发布，修改应用 @编辑项目发布表单页提示信息正确 @SUCCESS
- 编辑发布，修改名称、状态改为未开始、计划日期最终测试状态 @SUCCESS
- 编辑发布，修改名称、状态改为已发布、计划日期、发布日期最终测试状态 @SUCCESS
- 编辑发布，修改名称、状态改为停止维护最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/editprojectrelease.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$system = zenData('system');
$system->id->range('2');
$system->product->range('1');
$system->name->range('应用AAA, 应用BBB');
$system->status->range('active');
$system->createdBy->range('admin');
$system->gen(2);

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->attribute->range('[]');
$project->auth->range('[]');
