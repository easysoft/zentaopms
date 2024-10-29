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

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('1{1}, 2{1}');
$projectProduct->gen(2);

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->project->range('1');
$bug->product->range('1{5}, 2{5}');
$bug->execution->range('0');
$bug->title->range('Bug1, Bug2, Bug3, Bug4, Bug5, Bug6, Bug7, Bug8, Bug9, Bug10');
$bug->status->range('active{2}, resolved{2}, closed{1}, active{2}, resolved{2}, closed{1}');
$bug->assignedTo->range('[]');
$bug->gen(10);
