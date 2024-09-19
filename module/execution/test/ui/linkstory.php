<?php

/**
title=关联需求
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/linkstory.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-3');
$project->project->range('0, 1, 1');
$project->model->range('scrum, []{2}');
$project->type->range('project, sprint');
$project->auth->range('extend, []{2}');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`');
$project->grade->range('1');
$execution->name->range('项目1', '执行1', '执行2-无产品');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(3);
