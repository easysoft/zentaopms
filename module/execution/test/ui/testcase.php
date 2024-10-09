#!/usr/bin/env php
<?php

/**
title=执行下用例
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/testcase.ui.class.php';

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
$project->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1{2}, 2{2}');
$projectProduct->product->range('1, 2');
$projectProduct->gen(4);

$case = zenData('case');
$case->id->range('1-100');
$case->project->range('1{2}, 0{100}');
$case->product->range('1{10}, 2{5}');
$case->execution->range('0{5}, 2{10}');
$case->title->range('1-100');
$case->deleted->range('0{14}, 1');
$case->gen(15);

$projectCase = zenData('projectcase');
$projectCase->project->range('1{2}, 2{10}');
$projectCase->product->range('1{7}, 2{5}');
$projectCase->case->range('1-2, 6-15');
$projectCase->gen(12);

$tester = new testcaseTester();
$tester->login();

$tester->closeBrowser();
