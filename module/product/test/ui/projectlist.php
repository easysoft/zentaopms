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
