#!/usr/bin/env php
<?php

/**
title=关闭执行
timeout=0
cid=1
*/

chdir(__DIR__);
include '../lib/closeexecution.ui.class.php';

zenData('project')->loadYaml('execution', false, 2)->gen(10);
$execution = zenData('project');
$execution->id->range('110');
$execution->project->range('11');
$execution->type->range('sprint');
$execution->parent->range('11');
$execution->path->range('`,11,110,`');
$execution->grade->range('1');
$execution->name->range('测试关闭执行');
$execution->hasProduct->range('1');
$execution->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->realBegan->range('(-2D)-(+D):1D')->type('timestamp')->format('YY/MM/DD');
$execution->status->range('doing');
$execution->gen(1, false);

$tester = new closeExecutionTester();
$tester->login();

$realEnd = array(date('Y-m-d', strtotime('+20 days')), date('Y-m-d'), date('Y-m-d', strtotime('-4 days')));

r($tester->closeWithGreaterDate($realEnd[0])) && p('status,message') && e('SUCCESS,关闭执行表单页提示信息正确');
r($tester->close($realEnd[1]))                && p('status,message') && e('SUCCESS,关闭执行成功');
r($tester->closeWithLessDate($realEnd[2]))    && p('status,message') && e('SUCCESS,关闭执行表单页提示信息正确');
$tester->closeBrowser();
