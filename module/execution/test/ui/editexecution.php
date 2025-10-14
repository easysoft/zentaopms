#!/usr/bin/env php
<?php

/**

title=编辑执行
timeout=0
cid=1

- 编辑执行成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行成功
- 编辑看板成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行成功
- 执行名称为空，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行表单页提示信息正确
- 计划开始时间为空，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行表单页提示信息正确
- 计划结束时间为空，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行表单页提示信息正确
- 不同项目下同名执行，编辑成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行成功
- 执行名称重复，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行表单页提示信息正确
- 看板名称重复，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行表单页提示信息正确
- 执行的计划开始时间早于项目的计划开始时间，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行表单页提示信息正确
- 执行的计划结束时间晚于项目的计划结束时间，编辑失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行表单页提示信息正确
- 编辑执行产品成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑执行成功

 */

chdir(__DIR__);
include '../lib/ui/editexecution.ui.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->project->range('0');
$project->model->range('scrum, waterfall, kanban');
$project->type->range('project');
$project->auth->range('extend');
$project->storyType->range('story');
$project->parent->range('0');
$project->path->range('`,1,`, `,2,`, `,3,`');
$project->grade->range('1');
$project->name->range('敏捷项目, 瀑布项目, 看板项目');
$project->begin->range('(-6M)-(-5M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5M)-(+6M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('wait');
$project->gen(3);

$execution = zenData('project');
$execution->id->range('20-100');
$execution->project->range('1{2}, 2{2}, 3{2}');
$execution->model->range('[]');
$execution->type->range('sprint{2}, stage{2}, kanban{2}');
$execution->auth->range('[]');
$execution->storyType->range('[]');
$execution->parent->range('1{2}, 2{2}, 3{2}');
$execution->path->range('`,1,20,`, `,1,21,`, `,2,22,`, `,2,23,`, `,3,24,`, `,3,25,`');
$execution->grade->range('1');
$execution->name->range('执行1, 执行2, 阶段1, 阶段2, 看板1, 看板2');
$execution->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->openedBy->range('user1');
$execution->acl->range('open');
$execution->status->range('wait');
$execution->gen(6, false);

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-3, 20-25');
$projectProduct->product->range('1{9}, 2{3}');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(12);

$tester = new editExecutionTester();
$tester->login();

$execution = array(
    '0' => array(
        'id'    => '20',
        'name'  => '测试执行1',
        'begin' => date('Y-m-d', strtotime('+1 days')),
        'end'   => date('Y-m-d', strtotime('+3 days')),
    ),
    '1' => array(
        'id'    => '24',
        'name'  => '测试看板1',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '2' => array(
        'id'    => '20',
        'name'  => '',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '3' => array(
        'id'    => '20',
        'name'  => '测试执行2',
        'begin' => '',
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '4' => array(
        'id'    => '20',
        'name'  => '测试执行3',
        'begin' => date('Y-m-d'),
        'end'   => '',
    ),
    '5' => array(
        'id'    => '24',
        'name'  => '执行2',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '6' => array(
        'id'    => '20',
        'name'  => '执行2',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '7' => array(
        'id'    => '24',
        'name'  => '看板2',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '8' => array(
        'id'    => '20',
        'name'  => '测试执行4',
        'begin' => date('Y-m-d', strtotime('-2 years')),
        'end'   => date('Y-m-d', strtotime('+2 days')),
    ),
    '9' => array(
        'id'    => '20',
        'name'  => '测试执行5',
        'begin' => date('Y-m-d'),
        'end'   => date('Y-m-d', strtotime('+2 years')),
    ),
    '10' => array(
        'id'       => '20',
        'name'     => '测试执行6',
        'begin'    => date('Y-m-d'),
        'end'      => date('Y-m-d', strtotime('+2 days')),
        'products' => '产品2',
    )

);

r($tester->edit($execution['0']))                         && p('status,message') && e('SUCCESS,编辑执行成功');               //编辑执行成功
r($tester->edit($execution['1'], 'kanban'))               && p('status,message') && e('SUCCESS,编辑执行成功');               //编辑看板成功
r($tester->edit($execution['2']))                         && p('status,message') && e('SUCCESS,编辑执行表单页提示信息正确'); //执行名称为空，编辑失败
r($tester->edit($execution['3']))                         && p('status,message') && e('SUCCESS,编辑执行表单页提示信息正确'); //计划开始时间为空，编辑失败
r($tester->edit($execution['4']))                         && p('status,message') && e('SUCCESS,编辑执行表单页提示信息正确'); //计划结束时间为空，编辑失败
r($tester->edit($execution['5'], 'kanban'))               && p('status,message') && e('SUCCESS,编辑执行成功');               //不同项目下同名执行，编辑成功
r($tester->editWithRepeatName($execution['6']))           && p('status,message') && e('SUCCESS,编辑执行表单页提示信息正确'); //执行名称重复，编辑失败
r($tester->editWithRepeatName($execution['7'], 'kanban')) && p('status,message') && e('SUCCESS,编辑执行表单页提示信息正确'); //看板名称重复，编辑失败
r($tester->editWithDateError($execution['8'], 'begin'))   && p('status,message') && e('SUCCESS,编辑执行表单页提示信息正确'); //执行的计划开始时间早于项目的计划开始时间，编辑失败
r($tester->editWithDateError($execution['9'], 'end'))     && p('status,message') && e('SUCCESS,编辑执行表单页提示信息正确'); //执行的计划结束时间晚于项目的计划结束时间，编辑失败
r($tester->edit($execution['10']))                        && p('status,message') && e('SUCCESS,编辑执行成功');               //编辑执行产品成功
$tester->closeBrowser();
