#!/usr/bin/env php
<?php

/**
title=检查运营界面目标列表各tab下数据
timeout=0
cid=0

- 检查全部Tab
 - 测试结果 @全部Tab下数据显示正确
 - 最终测试状态 @SUCCESS
- 检查未关闭Tab
 - 测试结果 @未关闭Tab下数据显示正确
 - 最终测试状态 @SUCCESS
- 检查草稿Tab
 - 测试结果 @草稿Tab下数据显示正确
 - 最终测试状态 @SUCCESS
- 检查评审中Tab
 - 测试结果 @评审中Tab下数据显示正确
 - 最终测试状态 @SUCCESS
- 检查变更中Tab
 - 测试结果 @变更中Tab下数据显示正确
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/switchtabinlite.ui.class.php';

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

$tester->closeBrowser();
