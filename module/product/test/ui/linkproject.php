#!/usr/bin/env php
<?php

/**
title=关联项目
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
$project->id->range('1');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->gen(1);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('2');
$projectProduct->gen(1);
