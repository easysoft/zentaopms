<?php

/**
title=挂起执行
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/putoffexecution.ui.class.php';

$project = zenData('project');
$project->id->range('1');
$project->name->range('项目');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->hasProduct->range('1');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->gen(1);

$execution = zenData('project');
$execution->id->range('101,103');
$execution->project->range('1');
$execution->type->range('sprint');
$execution->parent->range('11');
$execution->path->range('`,1,101,`, `,1,103,`');
$execution->grade->range('1');
$execution->name->range('未开始执行, 进行中执行');
$execution->hasProduct->range('1');
$execution->begin->range('(-1M)-(-3W):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+1M)-(+2M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->days->range('10');
$execution->realBegan->range('(-2w)-(-1w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->status->range('wait, doing');
$execution->gen(2, false);

$tester = new putoffexecutionTester();
$tester->login();

$execution = array(
    '0' => array(),
    '1' => array(
        'begin' => date('Y-m-d', strtotime('-1 days')),
        'end'   => date('Y-m-d', strtotime('+1 days')),
    ),
    '2' => array(
        'begin' => '',
    ),
    '3' =>array(
        'end' => '',
    ),
    '4' => array(
        'begin' => date('Y-m-d', strtotime('-10 months')),
    ),
    '5' => array(
        'end' => date('Y-m-d', strtotime('+10 months')),
    ),
);

r($tester->putoff($execution['0'], '101'))                        && p('message') && e('延期执行成功');               //未开始的执行，延期弹窗直接点击保存
r($tester->putoff($execution['0'], '103'))                        && p('message') && e('延期执行成功');               //进行中的执行，延期弹窗直接点击保存
r($tester->putoff($execution['1'], '101'))                        && p('message') && e('延期执行成功');               //未开始的执行，延期弹窗中修改正确的起止日期后点击保存
r($tester->putoff($execution['1'], '103'))                        && p('message') && e('延期执行成功');               //进行中的执行，延期弹窗中修改正确的起止日期后点击保存
r($tester->putoffiWithWrongDate($execution['2'], '101', 'begin')) && p('message') && e('延期执行表单页提示信息正确'); //计划开始日期为空
r($tester->putoffiWithWrongDate($execution['3'], '103', 'emd'))   && p('message') && e('延期执行表单页提示信息正确'); //计划结束日期为空
r($tester->putoffiWithWrongDate($execution['4'], '103', 'begin')) && p('message') && e('延期执行表单页提示信息正确'); //计划开始日期小于项目计划开始日期
r($tester->putoffiWithWrongDate($execution['5'], '101', 'end'))   && p('message') && e('延期执行表单页提示信息正确'); //计划结束日期大于项目计划完成日期
$tester->closeBrowser();
