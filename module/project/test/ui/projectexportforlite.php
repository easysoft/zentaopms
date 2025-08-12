#!/usr/bin/env php
<?php

/**

title=导出项目
timeout=0
cid=73

- 按照默认设置导出项目
 - 测试结果 @项目导出成功
 - 最终测试状态 @SUCCESS
- 项目导出csv-GBK-选中记录
 - 测试结果 @项目导出成功
 - 最终测试状态 @SUCCESS
- 项目导出xml-全部记录
 - 测试结果 @项目导出成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/projectexportforlite.ui.class.php';

$project = zenData('project');
$project->id->range('1-3');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->hasProduct->range('0');
$project->name->range('运营项目1, 运营项目2, 运营项目3');
$project->path->range('`,1,`, `,2,`, `,3,`');
$project->vision->range('lite');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(3);

$product = zenData('product');
$product->id->range('1-3');
$product->program->range('0');
$product->name->range('运营项目1', '运营项目2', '运营项目3');
$product->shadow->range('1');
$product->bind->range('1');
$product->type->range('normal');
$product->gen(3);

$projectProduct = zenData('projectproduct');
