#!/usr/bin/env php
<?php

/**
title=单个执行测试单下用例
timeout=0
cid=1

- 执行tester模块的runCase方法，参数是true, 'n/a'?
 - 最终测试状态 @SUCCESS
 - 测试结果 @用例执行成功
- 执行tester模块的runCase方法，参数是true, 'pass'?
 - 最终测试状态 @SUCCESS
 - 测试结果 @用例执行成功
- 执行tester模块的runCase方法，参数是true, 'fail'?
 - 最终测试状态 @SUCCESS
 - 测试结果 @用例执行成功
- 执行tester模块的runCase方法，参数是true, 'blocked'?
 - 最终测试状态 @SUCCESS
 - 测试结果 @用例执行成功
- 执行tester模块的runCase方法，参数是false, 'n/a'?
 - 最终测试状态 @SUCCESS
 - 测试结果 @用例执行成功
- 执行tester模块的runCase方法，参数是false, 'pass'?
 - 最终测试状态 @SUCCESS
 - 测试结果 @用例执行成功
- 执行tester模块的runCase方法，参数是false, 'fail'?
 - 最终测试状态 @SUCCESS
 - 测试结果 @用例执行成功
- 执行tester模块的runCase方法，参数是false, 'blocked'?
 - 最终测试状态 @SUCCESS
 - 测试结果 @用例执行成功

 */

chdir(__DIR__);
include '../lib/ui/runcase.ui.class.php';

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
$case->story->range('0');
$case->title->range('1-100');
$case->stage->range('feature');
$case->status->range('normal,blocked,investigate,normal{100}');
$case->deleted->range('0{14}, 1');
$case->gen(15);

$casestep = zenData('casestep');
$casestep->id->range('1-100');
$casestep->parent->range('0');
$casestep->case->range('1-100');
$casestep->version->range('1');
$casestep->type->range('step');
$casestep->desc->range('1');
$casestep->expect->range('1');
$casestep->gen(3);

$projectCase = zenData('projectcase');
$projectCase->project->range('1{2}, 2{10}');
$projectCase->product->range('1{7}, 2{5}');
$projectCase->case->range('1-2, 6-15');
$projectCase->gen(12);

$testrun = zenData('testrun');
$testrun->id->range('1-100');
$testrun->task->range('1');
$testrun->case->range('1-100');
$testrun->version->range('1');
$testrun->assignedTo->range('admin{3}, []{100}');
$testrun->status->range('normal');
$testrun->gen(5);

$tester = new runCaseTester();
$tester->login();

/* 有用例步骤 */
r($tester->runCase(true, 'n/a'))     && p('status,message') && e('SUCCESS,用例执行成功');
r($tester->runCase(true, 'pass'))    && p('status,message') && e('SUCCESS,用例执行成功');
r($tester->runCase(true, 'fail'))    && p('status,message') && e('SUCCESS,用例执行成功');
r($tester->runCase(true, 'blocked')) && p('status,message') && e('SUCCESS,用例执行成功');
/* 没有用例步骤 */
r($tester->runCase(false, 'n/a'))     && p('status,message') && e('SUCCESS,用例执行成功');
r($tester->runCase(false, 'pass'))    && p('status,message') && e('SUCCESS,用例执行成功');
r($tester->runCase(false, 'fail'))    && p('status,message') && e('SUCCESS,用例执行成功');
r($tester->runCase(false, 'blocked')) && p('status,message') && e('SUCCESS,用例执行成功');
$tester->closeBrowser();
