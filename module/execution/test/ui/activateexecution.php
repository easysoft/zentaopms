#!/usr/bin/env php
<?php

/**
title=激活执行
timeout=0
cid=1
*/

chdir(__DIR__);
include '../lib/activateexecution.ui.class.php';

zenData('project')->loadYaml('execution', false, 2)->gen(1);
$execution = zenData('project');
$execution->id->range('101,103');
$execution->project->range('11');
$execution->type->range('sprint');
$execution->parent->range('11');
$execution->path->range('`,11,101,`, `,11,103,`');
$execution->grade->range('1');
$execution->name->range('挂起的执行, 已关闭的执行');
$execution->hasProduct->range('1');
$execution->begin->range('(-1D)-(-3D):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+1D)-(+2D):1D')->type('timestamp')->format('YY/MM/DD');
$execution->days->range('10');
$execution->status->range('suspended, closed');
$execution->gen(2, false);

$tester = new activateExecutionTester();
$tester->login();

$end = array(date('Y-m-d', strtotime('+1 days')), '', date('Y-m-d', strtotime('-10 months')), date('Y-m-d', strtotime('+10 months')));

r($tester->activateWithLessEnd($end[1], '101'))    && p('status,message') && e('SUCCESS,激活执行表单页提示信息正确'); //计划完成日期为空，激活失败
r($tester->activateWithLessEnd($end[2], '103'))    && p('status,message') && e('SUCCESS,激活执行表单页提示信息正确'); //计划完成日期小于计划开始日期，激活失败
r($tester->activateWithGreaterEnd($end[3], '101')) && p('status,message') && e('SUCCESS,激活执行表单页提示信息正确'); //计划完成日期大于项目的计划完成日期，激活失败
r($tester->activate($end[0], '101'))               && p('status,message') && e('SUCCESS,激活执行成功');               //成功激活挂起的执行
r($tester->activate($end[0], '103'))               && p('status,message') && e('SUCCESS,激活执行成功');               //成功激活已关闭的执行
$tester->closeBrowser();
