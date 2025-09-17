#!/usr/bin/env php
<?php

/**
title=运营界面关闭看板
timeout=0
cid=1

- 执行tester模块的closeWithGreaterDate方法，参数是$realEnd[0], '2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @关闭执行表单页提示信息正确
- 执行tester模块的closeWithLessDate方法，参数是$realEnd[2], '2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @关闭执行表单页提示信息正确
- 执行tester模块的close方法，参数是$realEnd[1], '2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @关闭执行成功

*/

chdir(__DIR__);
include '../lib/ui/closeinlite.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->shadow->range('1');
$product->type->range('normal');
$product->vision->range('lite');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->auth->range('extend');
$project->storytype->range('[]');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('项目1');
$project->hasProduct->range('1');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->status->range('doing');
$project->acl->range('open');
$project->vision->range('lite');
$project->gen(1);

$execution = zenData('project');
$execution->id->range('2');
$execution->project->range('1');
$execution->type->range('kanban');
$execution->parent->range('1');
$execution->path->range('`,1,2,`');
$execution->grade->range('1');
$execution->name->range('测试关闭看板');
$execution->hasProduct->range('1');
$execution->begin->range('(-5D)-(-4D):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+M)-(+2M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->realBegan->range('(-3D)-(-2D):1D')->type('timestamp')->format('YY/MM/DD');
$execution->status->range('doing');
$execution->vision->range('lite');
$execution->gen(1, false);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-5');
$projectProduct->product->range('1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(2);

zenData('task')->gen(0);

$tester = new closeExecutionTester();
$tester->login();

$realEnd = array(date('Y-m-d', strtotime('+20 days')), date('Y-m-d'), date('Y-m-d', strtotime('-4 days')));

r($tester->closeWithGreaterDate($realEnd[0], '2')) && p('status,message') && e('SUCCESS,关闭执行表单页提示信息正确');
r($tester->closeWithLessDate($realEnd[2], '2'))    && p('status,message') && e('SUCCESS,关闭执行表单页提示信息正确');
r($tester->close($realEnd[1], '2'))                && p('status,message') && e('SUCCESS,关闭执行成功');
$tester->closeBrowser();
