#!/usr/bin/env php
<?php

/**

title=运营界面创建看板
timeout=0
cid=1

- 创建看板成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建看板成功
- 看板名称为空，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建看板表单页提示信息正确
- 计划开始时间为空，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建看板表单页提示信息正确
- 计划结束时间为空，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建看板表单页提示信息正确
- 不同项目下同名看板、代号，创建成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建看板成功
- 看板名称重复，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建看板表单页提示信息正确
- 看板的计划开始时间早于项目的计划开始时间，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建看板表单页提示信息正确
- 看板的计划结束时间晚于项目的计划结束时间，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建看板表单页提示信息正确

 */

chdir(__DIR__);
include '../lib/ui/createinlite.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 0');
$project->model->range('kanban');
$project->type->range('project');
$project->auth->range('[]');
$project->storytype->range('[]');
$project->parent->range('0, 0');
$project->path->range('`,1,`, `,2,`');
$project->grade->range('1');
$project->name->range('运营项目1, 运营项目2');
$project->hasProduct->range('0');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->status->range('wait');
$project->acl->range('open');
$project->vision->range('lite');
$project->gen(2);

$product = zenData('product');
$product->id->range('1-100');
$product->program->range('0');
$product->name->range('运营项目1, 运营项目2');
$product->shadow->range('1');
$product->bind->range('1');
$product->type->range('normal');
$product->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-3');
$projectProduct->product->range('1, 2');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(2);

zenData('branch')->gen(0);
zenData('design')->gen(0);
zenData('story')->gen(0);
zenData('action')->gen(0);
zenData('actionrecent')->gen(0);
zenData('history')->gen(0);

$tester = new createExecutionTester();
$tester->login();

$execution = array(
    '0' => array(
        'name'     => '测试看板1',
        'project'  => '运营项目1',
        'begin'    => date('Y-m-d'),
        'end'      => date('Y-m-d', strtotime('+2 days')),
    ),
    '1' => array(
        'name'     => '',
        'project'  => '运营项目1',
        'begin'    => date('Y-m-d'),
        'end'      => date('Y-m-d', strtotime('+2 days')),
    ),
    '2' => array(
        'name'    => '测试看板4',
        'project' => '运营项目1',
        'begin'   => '',
        'end'     => date('Y-m-d', strtotime('+2 days')),
    ),
    '3' => array(
        'name'    => '测试看板5',
        'project' => '运营项目1',
        'begin'   => date('Y-m-d'),
        'end'     => '',
    ),
    '4' => array(
        'name'    => '测试看板1',
        'project' => '运营项目2',
        'begin'   => date('Y-m-d'),
        'end'     => date('Y-m-d', strtotime('+2 days')),
    ),
    '5' => array(
        'name'    => '测试看板1',
        'project' => '运营项目1',
        'begin'   => date('Y-m-d'),
        'end'     => date('Y-m-d', strtotime('+2 days')),
    ),
    '6' => array(
        'name'     => '测试看板8',
        'project'  => '运营项目1',
        'begin'    => date('Y-m-d', strtotime('-2 years')),
        'end'      => date('Y-m-d', strtotime('+2 days')),
        'products' => '',
    ),
    '7' => array(
        'name'     => '测试看板9',
        'project'  => '运营项目1',
        'begin'    => date('Y-m-d'),
        'end'      => date('Y-m-d', strtotime('+2 years')),
        'products' => '',
    ),
);

r($tester->create($execution['0']))                       && p('status,message') && e('SUCCESS,创建看板成功');               //创建看板成功
r($tester->createWithEmptyName($execution['1']))          && p('status,message') && e('SUCCESS,创建看板表单页提示信息正确'); //看板名称为空，创建失败
r($tester->create($execution['2']))                       && p('status,message') && e('SUCCESS,创建看板表单页提示信息正确'); //计划开始时间为空，创建失败
r($tester->create($execution['3']))                       && p('status,message') && e('SUCCESS,创建看板表单页提示信息正确'); //计划结束时间为空，创建失败
r($tester->create($execution['4']))                       && p('status,message') && e('SUCCESS,创建看板成功');               //不同项目下同名看板、代号，创建成功
r($tester->createWithRepeatName($execution['5']))         && p('status,message') && e('SUCCESS,创建看板表单页提示信息正确'); //看板名称重复，创建失败
r($tester->createWithDateError($execution['6'], 'begin')) && p('status,message') && e('SUCCESS,创建看板表单页提示信息正确'); //看板的计划开始时间早于项目的计划开始时间，创建失败
r($tester->createWithDateError($execution['7'], 'end'))   && p('status,message') && e('SUCCESS,创建看板表单页提示信息正确'); //看板的计划结束时间晚于项目的计划结束时间，创建失败
$tester->closeBrowser();
