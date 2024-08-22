<?php

/**
title=挂起执行
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/putoffexecution.ui.class.php';

zenData('project')->loadYaml('execution', false, 2)->gen(10);
$ptoject = zenData('project');
$project->id->range('1');
$project->name->range('项目');
$project->project->range('0');
$project->type->range('project');
$project->anth->range('extend');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');

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
#r($tester->putoff($execution['0'], '103'))                        && p('message') && e('延期执行成功');               //进行中的执行，延期弹窗直接点击保存
#r($tester->putoff($execution['1'], '101'))                        && p('message') && e('延期执行成功');               //未开始的执行，延期弹窗中修改正确的起止日期后点击保存
#r($tester->putoff($execution['1'], '103'))                        && p('message') && e('延期执行成功');               //进行中的执行，延期弹窗中修改正确的起止日期后点击保存
#r($tester->putoffiWithWrongDate($execution['2'], '101', 'begin')) && p('message') && e('延期执行表单页提示信息正确'); //计划开始日期为空
#r($tester->putoffiWithWrongDate($execution['3'], '103', 'emd'))   && p('message') && e('延期执行表单页提示信息正确'); //计划结束日期为空
#r($tester->putoffiWithWrongDate($execution['4'], '103', 'begin')) && p('message') && e('延期执行表单页提示信息正确'); //计划开始日期小于项目计划开始日期
#r($tester->putoffiWithWrongDate($execution['5'], '101', 'end'))   && p('message') && e('延期执行表单页提示信息正确'); //计划结束日期大于项目计划完成日期
$tester->closeBrowser();
