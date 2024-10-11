#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/browse.ui.class.php';

$project = zendata('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('waterfall');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->name->range('瀑布项目1');
$project->path->range('`,1,`');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(1);

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('1{1}, 2{1}');
$projectProduct->gen(2);

$design = zendata('design');
$design->id->range('1-4');
$design->project->range('1{4}');
$design->product->range('1{2}, 2{2}');
$design->name->range('概要设计1, 详细设计1, 数据库设计1, 接口设计1');
$design->type->range('HLDS, DDS, DBDS, ADS');
$design->gen(4);
