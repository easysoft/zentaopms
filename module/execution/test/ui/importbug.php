<?php

/**
title=导入Bug
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/importbug.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1, 1');
$project->model->range('scrum, []{2}');
$project->type->range('project, sprint{2}');
$project->auth->range('extend, []{2}');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`');
$project->grade->range('1');
$project->name->range('项目1, 项目1执行1, 项目1执行2, 项目1执行3');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(4);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-3');
$projectProduct->product->range('1{3}, 2{2}');
$projectProduct->gen(5);

$bug = zenData('bug');
$bug->id->range('1-100');
$bug->project->range('0');
$bug->product->range('1{5}, 2{5}');
$bug->execution->range('0');
$bug->task->range('0');
$bug->toTask->range('0');
$bug->title->range('1-100');
$bug->status->range('active{3}, resolved, closed');
$bug->deleted->range('1, 0{4}');
$bug->gen(10);
