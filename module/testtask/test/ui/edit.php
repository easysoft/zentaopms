#!/usr/bin/env php
<?php

/**
title=编辑测试单
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/ui/edit.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{3}');
$project->model->range('scrum, []{3}');
$project->type->range('project, sprint{3}');
$project->auth->range('extend, []{3}');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`');
$project->grade->range('1');
$project->name->range('项目1, 项目1执行1, 项目1执行2, 项目1执行3');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(4);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1, 2, 3, 4');
$projectProduct->product->range('1{4}, 2{4}');
$projectProduct->gen(8);

$build = zenData('build');
$build->id->range('1-100');
$build->project->range('1');
$build->product->range('1');
$build->branch->range('0');
$build->execution->range('2{4}, 3{2}');
$build->name->range('构建1, 构建2, 构建3, 构建4, 构建5, 构建6');
$build->scmPath->range('[]');
$build->filePath->range('[]');
$build->deleted->range('0');
$build->gen(6);

$testtask = zenData('testtask');
$testtask->id->range('1-100');
$testtask->project->range('1');
$testtask->product->range('1, 2');
$testtask->name->range('测试单1, 测试单2, 测试单3, 测试单4, 测试单5, 测试单6');
$testtask->execution->range('2{4}, 3{2}');
$testtask->build->range('1-6');
$testtask->status->range('wait{5}, doing{5}, done{3}, blocked{2}');
$testtask->deleted->range('0');
$testtask->gen(1);

$tester = new editTester();
$tester->login();

$testtask = array(
    '0' => array(
        'execution' => '执行3',
        'name'      => '编辑测试单1'
    ),
    '1' => array(
        'build' => '构建2',
        'begin' => '',
        'end'   => date('Y-m-d', strtotime('+1 day')),
        'name'  => '编辑测试单2'
    ),
    '2' => array(
        'build' => '构建2',
        'begin' => date('Y-m-d'),
        'end'   => '',
        'name'  => '编辑测试单3'
    ),
    '3' => array(
        'build' => '构建2',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+1 day')),
        'name'  => ''
    ),
    '4' => array(
        'build' => '构建2',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('-1 day')),
        'name'  => '编辑测试单5'
    ),
    '5' => array(
        'build' => '构建2',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+1 day')),
        'name'  => '编辑测试单6'
    )
);

r($tester->edit($testtask['0'])) && p('status,message') && e('SUCCESS,提测构建为空时提示信息正确');
r($tester->edit($testtask['1'])) && p('status,message') && e('SUCCESS,开始日期为空时提示信息正确');
r($tester->edit($testtask['2'])) && p('status,message') && e('SUCCESS,结束日期为空时提示信息正确');
r($tester->edit($testtask['3'])) && p('status,message') && e('SUCCESS,测试单名称为空时提示信息正确');
r($tester->edit($testtask['4'])) && p('status,message') && e('SUCCESS,开始日期大于结束日期时提示信息正确');
r($tester->edit($testtask['5'])) && p('status,message') && e('SUCCESS,编辑测试单成功');

$tester->closeBrowser();
