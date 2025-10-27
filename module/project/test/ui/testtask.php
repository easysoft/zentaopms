#!/usr/bin/env php
<?php

/**

title=项目下测试单列表数据检查
timeout=0
cid=1

- 执行tester模块的checkNum方法，参数是false, array
 - 最终测试状态 @SUCCESS
 - 测试结果 @测试单列表统计数据正确
- 执行tester模块的checkNum方法，参数是true, array
 - 最终测试状态 @SUCCESS
 - 测试结果 @测试单列表统计数据正确
- 执行tester模块的createReport方法▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @不同产品测试单生成测试报告提示信息正确

 */

chdir(__DIR__);
include '../lib/ui/testtask.ui.class.php';

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-3');
$project->project->range('0, 1, 1');
$project->model->range('scrum, []{2}');
$project->type->range('project, sprint{2}');
$project->auth->range('extend, []{2}');
$project->storytype->range('`story,epic,requirement`');
$project->parent->range('0, 1, 1');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`');
$project->grade->range('1');
$project->name->range('敏捷项目1, 迭代1, 迭代2');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-6');
$projectProduct->product->range('1, 2');
$projectProduct->gen(6);

$system = zenData('system');
$system->id->range('1-2');
$system->name->range('应用1, 应用2');
$system->product->range('1, 2');
$system->status->range('active');
$system->gen(2);

$build = zenData('build');
$build->id->range('1-6');
$build->project->range('1');
$build->product->range('1, 2');
$build->branch->range('0');
$build->execution->range('2{3}, 3{3}');
$build->name->range('构建1, 构建2, 构建3, 构建4, 构建5, 构建6');
$build->system->range('1{3}, 2{3}');
$build->scmPath->range('[]');
$build->filePath->range('[]');
$build->deleted->range('0');
$build->gen(6);

$testtask = zenData('testtask');
$testtask->id->range('1-6');
$testtask->project->range('1');
$testtask->product->range('1, 2');
$testtask->name->range('测试单1, 测试单2, 测试单3, 测试单4, 测试单5, 测试单6,');
$testtask->execution->range('2{3}, 3{3}');
$testtask->build->range('1-6');
$testtask->status->range('wait{2}, doing{1}, done{2}, blocked{1}');
$testtask->deleted->range('0');
$testtask->gen(6);

$tester = new testtaskTester();
$tester->login();

/* 检查测试单列表统计数据 */
r($tester->checkNum(false, array(6, 2, 1, 1, 2))) && p('status,message') && e('SUCCESS,测试单列表统计数据正确');
r($tester->checkNum(true, array(6, 2, 1, 1, 2)))  && p('status,message') && e('SUCCESS,测试单列表统计数据正确');
/* 检查不同产品测试单生成测试报告提示信息 */
r($tester->createReport()) && p('status,message') && e('SUCCESS,不同产品测试单生成测试报告提示信息正确');

$tester->closeBrowser();
