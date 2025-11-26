#!/usr/bin/env php
<?php

/**
title=测试单关联用例
timeout=0
cid=1

- 执行tester模块的linkCase方法，参数是9?
 - 最终测试状态 @SUCCESS
 - 测试结果 @测试单关联用例成功
- 执行tester模块的linkCase方法，参数是8?
 - 最终测试状态 @SUCCESS
 - 测试结果 @测试单关联用例成功

 */

chdir(__DIR__);
include '../lib/ui/linkcase.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{3}');
$project->model->range('scrum, []{3}');
$project->type->range('project, sprint{3}');
$project->auth->range('extend, []{3}');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`');
$project->grade->range('1');
$project->name->range('项目1, 项目1执行1, 项目1执行2, 项目1执行3');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(4);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1, 2, 3, 4');
$projectProduct->product->range('1{4}, 2{4}');
$projectProduct->gen(8);

$build = zenData('build');
$build->id->range('1-100');
$build->project->range('1');
$build->product->range('1');
$build->branch->range('0');
$build->execution->range('2{4}, 3{2}');
$build->name->range('构建1, 构建2, 构建3, 构建4, 构建5, 构建6');
$build->scmPath->range('[]');
$build->filePath->range('[]');
$build->deleted->range('0');
$build->gen(1);

$testtask = zenData('testtask');
$testtask->id->range('1-100');
$testtask->project->range('1');
$testtask->product->range('1');
$testtask->name->range('测试单1, 测试单2, 测试单3, 测试单4, 测试单5, 测试单6');
$testtask->execution->range('2{4}, 3{2}');
$testtask->build->range('1-6');
$testtask->begin->range('(-2D)-(-D):1D')->type('timestamp')->format('YY/MM/DD');
$testtask->end->range('(+D)-(+2D):1D')->type('timestamp')->format('YY/MM/DD');
$testtask->status->range('wait{5}, doing{5}, done{3}, blocked{2}');
$testtask->deleted->range('0');
$testtask->gen(1);

$case = zenData('case');
$case->id->range('1-100');
$case->project->range('1{2}, 0{100}');
$case->product->range('1{10}, 2{5}');
$case->execution->range('0{5}, 2{10}');
$case->title->range('1-100');
$case->status->range('wait,normal,blocked,investigate,normal{100}');
$case->deleted->range('0{14}, 1');
$case->gen(15);

$projectCase = zenData('projectcase');
$projectCase->project->range('1{2}, 2{10}');
$projectCase->product->range('1{7}, 2{5}');
$projectCase->case->range('1-2, 6-15');
$projectCase->gen(12);

$testrun = zenData('testrun');
$testrun->gen(0);

$tester = new linkCaseTester();
$tester->login();

r($tester->linkCase(9)) && p('status,message') && e('SUCCESS,测试单关联用例成功');
r($tester->linkCase(8)) && p('status,message') && e('SUCCESS,测试单关联用例成功');
$tester->closeBrowser();
