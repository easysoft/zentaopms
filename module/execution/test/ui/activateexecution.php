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
