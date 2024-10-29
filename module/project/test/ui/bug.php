#!/usr/bin/env php
<?php

/**

title=项目下bug列表操作检查
timeout=0
cid=1

- 执行tester模块的checkTab方法，参数是'allTab', '10'
 - 最终测试状态 @SUCCESS
 - 测试结果 @allTab下显示条数正确
- 执行tester模块的checkTab方法，参数是'unresolvedTab', '4'
 - 最终测试状态 @SUCCESS
 - 测试结果 @unresolvedTab下显示条数正确
- 执行tester模块的switchProduct方法，参数是'firstProduct', '5'
 - 最终测试状态 @SUCCESS
 - 测试结果 @切换firstProduct查看数据成功
- 执行tester模块的switchProduct方法，参数是'secondProduct', '5'
 - 最终测试状态 @SUCCESS
 - 测试结果 @切换secondProduct查看数据成功

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
