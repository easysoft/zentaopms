#!/usr/bin/env php
<?php

/**
 *
 * title=检查产品看板数据准确性
 * timeout=0
 * cid=0
 *
 */
chdir(__DIR__);
include '../lib/kanban.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-6');
$project->project->range('0{3},1{3}');
$project->model->range('scrum{3},[]{3}');
$project->type->range('project{3},sprint{3}');
$project->parent->range('0{3},1{3}');
$project->auth->range('extend');
$project->storytype->range('story');
$project->path->range('`,1,`','`,2,`','`,3,`,`,1,4,`','`,1,5,`','`,1,6,`');
$project->grade->range('1');
$project->name->range('项目1,项目2,项目3,迭代1,迭代2,迭代3');
$project->hasProduct->range('1');
$project->status->range('doing{2},wait{1},doing{2},wait{1}');
$project->gen(6);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-6');
$projectProduct->product->range('1');
