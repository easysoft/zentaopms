#!/usr/bin/env php
<?php

/**

title=运营界面编辑看板
timeout=0
cid=1

- 编辑看板成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑看板成功
- 看板名称为空，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑看板表单页提示信息正确
- 计划开始时间为空，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑看板表单页提示信息正确
- 计划结束时间为空，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑看板表单页提示信息正确
- 看板名称重复，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑看板表单页提示信息正确
- 看板的计划开始时间早于项目的计划开始时间，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑看板表单页提示信息正确
- 看板的计划结束时间晚于项目的计划结束时间，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑看板表单页提示信息正确

 */

chdir(__DIR__);
include '../lib/ui/editinlite.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{2}');
$project->model->range('kanban, []{2}');
$project->type->range('project, kanban{2}');
$project->auth->range('[]');
$project->storyType->range('[]');
$project->parent->range('0, 1{2}');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`');
$project->grade->range('1');
$project->name->range('项目, 看板1, 看板2');
$project->hasProduct->range('0');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('wait');
$project->vision->range('lite');
$project->gen(3);

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('项目');
$product->shadow->range('1');
$product->bind->range('1');
$product->type->range('normal');
$product->vision->range('lite');
$product->gen(1);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-3');
$projectProduct->product->range('1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(3);

$tester = new editExecutionTester();
$tester->login();

$execution = array(
    '0' => array(
        'name'  => '编辑看板1',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '1' => array(
        'name'  => '',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '2' => array(
        'name'  => '编辑看板2',
        'begin' => '',
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '3' => array(
        'name'  => '编辑看板3',
        'begin' => date('Y-m-d'),
        'end'   => '',
    ),
    '4' => array(
        'name'  => '看板2',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '5' => array(
        'name'   => '编辑看板5',
        'begin'  => date('Y-m-d', strtotime('-2 years')),
        'end'    => date('Y-m-d', strtotime('+2 days')),
    ),
    '6' => array(
        'name'   => '测试看板9',
        'begin'  => date('Y-m-d'),
        'end'    => date('Y-m-d', strtotime('+2 years')),
    ),
);

r($tester->edit($execution['0']))                       && p('status,message') && e('SUCCESS,编辑看板成功');               //编辑看板成功
r($tester->editWithEmptyName($execution['1']))          && p('status,message') && e('SUCCESS,编辑看板表单页提示信息正确'); //看板名称为空，编辑失败
r($tester->edit($execution['2']))                       && p('status,message') && e('SUCCESS,编辑看板表单页提示信息正确'); //计划开始时间为空，编辑失败
r($tester->edit($execution['3']))                       && p('status,message') && e('SUCCESS,编辑看板表单页提示信息正确'); //计划结束时间为空，编辑失败
r($tester->editWithRepeatName($execution['4']))         && p('status,message') && e('SUCCESS,编辑看板表单页提示信息正确'); //看板名称重复，编辑失败
r($tester->editWithDateError($execution['5'], 'begin')) && p('status,message') && e('SUCCESS,编辑看板表单页提示信息正确'); //看板的计划开始时间早于项目的计划开始时间，编辑失败
r($tester->editWithDateError($execution['6'], 'end'))   && p('status,message') && e('SUCCESS,编辑看板表单页提示信息正确'); //看板的计划结束时间晚于项目的计划结束时间，编辑失败
$tester->closeBrowser();
