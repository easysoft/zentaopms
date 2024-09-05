<?php

/**
title=批量编辑执行
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/batchedit.ui.class.php';

$project = zenData('project');
$project->id->range('1-2');
$project->project->range('0');
$project->model->range('scrum{2}');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->name->range('项目1, 项目2');
$project->path->range('`,1,`, `,2,`');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(2);

$execution = zenData('project');
$execution->id->range('5-9');
$execution->project->range('1{3}, 2{2}');
$execution->type->range('sprint');
$execution->attribute->range('[]');
$execution->auth->range('[]');
$execution->parent->range('1{3}, 2{2}');
$execution->grade->range('1');
$execution->name->range('项目1迭代1, 项目1迭代2, 项目1迭代3, 项目2迭代1, 项目2迭代2');
$execution->begin->range('(-4w)-(-w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+7w)-(+8w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->acl->range('open');
$execution->status->range('wait');
$execution->gen(5, false);

$tester = new batchEditTester();
$tester->login();

$execution = array(
    '0' => array(
        'name'  => '项目1迭代2',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+1 days')),
    ),
    '1' => array(
        'name'  => '',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+1 days')),
    ),
    '2' => array(
        'name'  => '项目1迭代1',
        'begin' => '',
        'end'   => date('Y-m-d', strtotime('+1 days')),
    ),
    '3' => array(
        'name'  => '项目1迭代1',
        'begin' => date('Y-m-d', strtotime('-1 years')),
        'end'   => date('Y-m-d', strtotime('+1 days')),
    ),
    '4' => array(
        'name'  => '项目1迭代1',
        'begin' => date('Y-m-d'),
        'end'   => '',
    ),
    '5' => array(
        'name'  => '项目1迭代1',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+1 years')),
    ),
    '6' => array(
        'name'  => '项目1迭代1',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('-1 days')),
    ),
    '7' => array(
        'name'  => '项目1迭代2',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+1 days')),
    )
);

r($tester->checkName($execution['0']))          && p('message') && e('执行名称重复提示信息正确');
r($tester->checkName($execution['1']))          && p('message') && e('执行名称为空提示信息正确');
r($tester->checkDate($execution['2'], 'begin')) && p('message') && e('执行开始日期为空提示信息正确');
r($tester->checkDate($execution['3'], 'begin')) && p('message') && e('执行开始日期小于项目开始日期提示信息正确');
r($tester->checkDate($execution['4'], 'end'))   && p('message') && e('执行结束时间为空提示信息正确');
r($tester->checkDate($execution['5'], 'end'))   && p('message') && e('执行结束日期大于项目结束日期提示信息正确');
r($tester->checkDate($execution['6'], 'other')) && p('message') && e('执行结束日期小于执行开始日期提示信息正确');
r($tester->batchEdit($execution['7']))          && p('message') && e('批量编辑执行成功');
$tester->closeBrowser();
