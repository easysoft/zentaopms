#!/usr/bin/env php
<?php

/**

title=运营界面指派目标
timeout=0
cid=0

- 指派目标
 - 测试结果 @目标指派成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/assigninlite.ui.class.php';

$project = zenData('project');
$project->id->range('1');
$project->model->range('kanban');
$project->type->range('project');
$project->name->range('运营项目');
$project->hasProduct->range('0');
$project->acl->range('open');
$project->vision->range('lite');
$project->gen(1);

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('运营项目');
$product->shadow->range('1');
$product->type->range('normal');
$product->acl->range('open');
$product->vision->range('lite');
$product->gen(1);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1');
$projectproduct->product->range('1');
$projectproduct->branch->range('0');
$projectproduct->plan->range('0');
$projectproduct->gen(1);

$team = ZenData('team');
$team->id->range('1');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin');
$team->gen(1);
