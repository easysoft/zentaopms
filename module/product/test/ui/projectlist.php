#!/usr/bin/env php
<?php

/**
title=检查产品下项目列表
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/project.ui.class.php';

$product = zenData('product');
$product->id->range('1-2');
$product->program->range('0');
$product->name->range('产品1,产品2');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-10');
$project->project->range('0');
$project->model->range('scrum{5},kanban{2},waterfall{3}');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->path->range('`,1,`,`,2,`,`,3,`,`,4,`,`,5,`,`,6,`,`,7,`,`,8,`,`,9,`,`,10,`');
$project->grade->range('1');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->hasProduct->range('1');
$project->status->range('doing{1},suspended{3},closed{4},wait{2}');
$project->gen(10);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-10');
$projectProduct->product->range('1{9},2');
$projectProduct->gen(10);

$tester = new projectTester();
$tester->login();
$projecturl['status']    = 'all';
$projecturl['productID'] = 1;

r($tester->switchTab($projecturl, 'all', '9'))        && p('message,status') && e('all标签下项目数显示正确,SUCCESS');//检查全部标签下项目数
r($tester->switchTab($projecturl, 'unfinished', '2')) && p('message,status') && e('unfinished标签下项目数显示正确,SUCCESS');//检查未完成标签下项目数
r($tester->switchTab($projecturl, 'waiting', '1'))    && p('message,status') && e('waiting标签下项目数显示正确,SUCCESS');//检查未开始标签下项目数
r($tester->switchTab($projecturl, 'doing', '1'))      && p('message,status') && e('doing标签下项目数显示正确,SUCCESS');//检查进行中标签下项目数
r($tester->switchTab($projecturl, 'suspended', '3'))  && p('message,status') && e('suspended标签下项目数显示正确,SUCCESS');//检查已挂起标签下项目数
r($tester->switchTab($projecturl, 'closed', '4'))     && p('message,status') && e('closed标签下项目数显示正确,SUCCESS');//检查已关闭标签下项目数

$tester->closeBrowser();
