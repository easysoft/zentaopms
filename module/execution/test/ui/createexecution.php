#!/usr/bin/env php
<?php

/**

title=创建执行
timeout=0
cid=1

- 创建执行成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行成功
- 创建看板成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行成功
- 执行名称为空，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行表单页提示信息正确
- 计划开始时间为空，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行表单页提示信息正确
- 计划结束时间为空，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行表单页提示信息正确
- 不同项目下同名执行，创建成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行成功
- 执行名称重复，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行表单页提示信息正确
- 看板名称重复，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行表单页提示信息正确
- 创建阶段关联产品为空，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行表单页提示信息正确
- 执行的计划开始时间早于项目的计划开始时间，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行表单页提示信息正确
- 执行的计划结束时间晚于项目的计划结束时间，创建失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建执行表单页提示信息正确

*/

chdir(__DIR__);
include '../lib/ui/createexecution.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0');
$project->model->range('scrum, waterfall, kanban');
$project->type->range('project');
$project->auth->range('extend');
$project->storyType->range('story');
$project->parent->range('0');
$project->path->range('`,1,`, `,2,`, `,3,`');
$project->grade->range('1');
$project->name->range('敏捷项目, 瀑布项目, 看板项目');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('wait');
$project->gen(3);

$tester = new createExecutionTester();
$tester->login();

$execution = array(
    '0' => array(
        'name'     => '测试执行1',
        'project'  => '敏捷项目',
        'begin'    => date('Y-m-d'),
        'end'      => date('Y-m-d', strtotime('+2 days')),
        'products' => '',
    ),
    '1' => array(
        'name'     => '测试看板1',
        'project'  => '看板项目',
        'begin'    => date('Y-m-d'),
        'end'      => date('Y-m-d', strtotime('+2 days')),
        'products' => '',
    ),
    '2' => array(
        'name'    => '',
        'project' => '敏捷项目',
        'begin'   => date('Y-m-d'),
        'end'     => date('Y-m-d', strtotime('+2 days')),
    ),
    '3' => array(
        'name'    => '测试执行2',
        'project' => '敏捷项目',
        'begin'   => '',
        'end'     => date('Y-m-d', strtotime('+2 days')),
    ),
    '4' => array(
        'name'    => '测试执行3',
        'project' => '敏捷项目',
        'begin'   => date('Y-m-d'),
        'end'     => '',
    ),
    '5' => array(
        'name'    => '测试执行1',
        'project' => '看板项目',
        'begin'   => date('Y-m-d'),
        'end'     => date('Y-m-d', strtotime('+2 days')),
    ),
    '6' => array(
        'name'    => '测试执行1',
        'project' => '敏捷项目',
        'begin'   => date('Y-m-d'),
        'end'     => date('Y-m-d', strtotime('+2 days')),
    ),
    '7' => array(
        'name'    => '测试看板1',
        'project' => '看板项目',
        'begin'   => date('Y-m-d'),
        'end'     => date('Y-m-d', strtotime('+2 days')),
    ),
    '8' => array(
        'name'     => '测试阶段1',
        'project'  => '瀑布项目',
        'begin'    => date('Y-m-d'),
        'end'      => date('Y-m-d', strtotime('+2 days')),
    ),
    '9' => array(
        'name'     => '测试执行4',
        'project'  => '敏捷项目',
        'begin'    => date('Y-m-d', strtotime('-2 years')),
        'end'      => date('Y-m-d', strtotime('+2 days')),
        'products' => '',
    ),
    '10' => array(
        'name'     => '测试执行5',
        'project'  => '敏捷项目',
        'begin'    => date('Y-m-d'),
        'end'      => date('Y-m-d', strtotime('+2 years')),
        'products' => '',
    ),
);

r($tester->create($execution['0']))                         && p('status,message') && e('SUCCESS,创建执行成功');               //创建执行成功
r($tester->create($execution['1'], 'kanban'))               && p('status,message') && e('SUCCESS,创建执行成功');               //创建看板成功
r($tester->create($execution['2']))                         && p('status,message') && e('SUCCESS,创建执行表单页提示信息正确'); //执行名称为空，创建失败
r($tester->create($execution['3']))                         && p('status,message') && e('SUCCESS,创建执行表单页提示信息正确'); //计划开始时间为空，创建失败
r($tester->create($execution['4']))                         && p('status,message') && e('SUCCESS,创建执行表单页提示信息正确'); //计划结束时间为空，创建失败
r($tester->create($execution['5'], 'kanban'))               && p('status,message') && e('SUCCESS,创建执行成功');               //不同项目下同名执行，创建成功
r($tester->createWithRepeatName($execution['6']))           && p('status,message') && e('SUCCESS,创建执行表单页提示信息正确'); //执行名称重复，创建失败
r($tester->createWithRepeatName($execution['7'], 'kanban')) && p('status,message') && e('SUCCESS,创建执行表单页提示信息正确'); //看板名称重复，创建失败
r($tester->createWithNoProducts($execution['8']))           && p('status,message') && e('SUCCESS,创建执行表单页提示信息正确'); //创建阶段关联产品为空，创建失败
r($tester->createWithDateError($execution['9'], 'begin'))   && p('status,message') && e('SUCCESS,创建执行表单页提示信息正确'); //执行的计划开始时间早于项目的计划开始时间，创建失败
r($tester->createWithDateError($execution['10'], 'end'))    && p('status,message') && e('SUCCESS,创建执行表单页提示信息正确'); //执行的计划结束时间晚于项目的计划结束时间，创建失败
$tester->closeBrowser();
