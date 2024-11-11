#!/usr/bin/env php
<?php

/**

title=项目版本列表
timeout=0
cid=73

- 查看产品1下的版本
 - 最终测试状态 @SUCCESS
 - 测试结果 @版本显示正确
- 切换产品，查看产品2下版本
 - 最终测试状态 @SUCCESS
 - 测试结果 @版本显示正确
- 项目版本列表搜索版本
 - 最终测试状态 @SUCCESS
 - 测试结果 @项目版本搜索成功

*/
chdir(__DIR__);
include '../lib/browse.ui.class.php';

$product = zenData('product');
$product->id->range('1,2');
$product->name->range('产品1,产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->attribute->range('[]');
$project->auth->range('[]');
$project->parent->range('0');
$project->grade->range('1');
$project->name->range('敏捷项目1');
$project->path->range('`,1,`');
$project->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->status->range('wait');
$project->gen(1);

$execution = zenData('project');
$execution->id->range('2-3');
$execution->project->range('1');
$execution->type->range('sprint');
$execution->attribute->range('[]');
$execution->auth->range('[]');
$execution->parent->range('1');
$execution->grade->range('1');
$execution->name->range('项目1迭代1, 项目1迭代2');
$execution->path->range('`,1,2,`, `,1,3,`');
$execution->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->acl->range('open');
$execution->status->range('wait');
$execution->gen(2, false);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1{2},2{2}');
$projectProduct->product->range('1,2');
$projectProduct->gen(4);

$build = zenData('build');
$build->id->range('1-6');
$build->project->range('1');
$build->product->range('1{1},2{5}');
$build->branch->range('0');
$build->execution->range('2');
$build->name->range('版本1,版本2,版本3,版本4,版本5,版本6');
$build->stories->range('[]');
$build->bugs->range('[]');
$build->scmPath->range('[]');
$build->filePath->range('[]');
$build->desc->range('描述111');
$build->builder->range('admin');
$build->deleted->range('0');
$build->gen(6);

$tester = new browseTester();
$tester->login();

r($tester->switchProduct('产品1', '1')) && p('status,message') && e('SUCCESS,版本显示正确');     //查看产品1下的版本
r($tester->switchProduct('产品2', '5')) && p('status,message') && e('SUCCESS,版本显示正确');     //切换产品，查看产品2下版本
r($tester->searchBuild('版本2'))        && p('status,message') && e('SUCCESS,项目版本搜索成功'); //项目版本列表搜索版本

$tester->closeBrowser();
