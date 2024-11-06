#!/usr/bin/env php
<?php

/**
title=树状图
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/tree.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1');
$project->model->range('scrum, []');
$project->type->range('project, sprint');
$project->auth->range('extend, []');
$project->storyType->range('story, []');
$project->parent->range('0, 1');
$project->path->range('`,1,`, `,1,2,`');
$project->grade->range('1');
$project->name->range('项目, 执行');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing');
$project->gen(2);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1{2}, 2{2}');
$projectproduct->product->range('1, 2');
$projectproduct->gen(4);
