#!/usr/bin/env php
<?php

/**
title=关闭执行
timeout=0
cid=1
*/

chdir(__DIR__);
include '../lib/closeexecution.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->auth->range('extend');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('项目1');
$project->hasProduct->range('1');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(1);

$execution = zenData('project');
$execution->id->range('2');
$execution->project->range('1');
$execution->type->range('sprint');
$execution->parent->range('1');
$execution->path->range('`,1,2,`');
$execution->grade->range('1');
$execution->name->range('测试关闭执行');
$execution->hasProduct->range('1');
$execution->begin->range('(-5D)-(-4D):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+M)-(+2M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->realBegan->range('(-3D)-(-2D):1D')->type('timestamp')->format('YY/MM/DD');
$execution->status->range('doing');
$execution->gen(1, false);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-5');
$projectProduct->product->range('1');
$projectProduct->gen(2);


$tester = new closeExecutionTester();
$tester->login();

$realEnd = array(date('Y-m-d', strtotime('+20 days')), date('Y-m-d'), date('Y-m-d', strtotime('-4 days')));

r($tester->closeWithGreaterDate($realEnd[0], '2')) && p('status,message') && e('SUCCESS,关闭执行表单页提示信息正确');
r($tester->closeWithLessDate($realEnd[2], '2'))    && p('status,message') && e('SUCCESS,关闭执行表单页提示信息正确');
r($tester->close($realEnd[1], '2'))                && p('status,message') && e('SUCCESS,关闭执行成功');
$tester->closeBrowser();
