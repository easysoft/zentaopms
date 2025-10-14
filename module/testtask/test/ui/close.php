#!/usr/bin/env php
<?php

/**
title=关闭测试单
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/ui/close.ui.class.php';

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
$build->gen(6);

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
$testtask->gen(2);

$tester = new closeTester();
$tester->login();

r($tester->close(date('Y-m-d', strtotime('-1 year')))) && p('status,message') && e('SUCCESS,实际完成日期小于开始日期时提示正确');
r($tester->close(date('Y-m-d', strtotime('+20 day')))) && p('status,message') && e('SUCCESS,实际完成日期大于当前日期时提示正确');
r($tester->close(date('Y-m-d')))                       && p('status,message') && e('SUCCESS,关闭成功');

$tester->closeBrowser();
