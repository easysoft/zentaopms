#!/usr/bin/env php
<?php

/**

title=设置瀑布项目阶段测试
timeout=0
cid=1

 校验阶段名称不能为空
 - 测试结果 @阶段名称不能为空提示信息正确
 - 最终测试状态 @SUCCESS
- 校验计划开始必填
 - 测试结果 @计划开始日期不能为空提示信息正确
 - 最终测试状态 @SUCCESS
- 校验计划完成必填
 - 测试结果 @计划结束日期不能为空提示信息正确
 - 最终测试状态 @SUCCESS
- 校验计划完成必须大于计划开始
 - 测试结果 @计划开始不能大于计划完成提示信息正确
 - 最终测试状态 @SUCCESS
- 创建需求阶段
 - 测试结果 @创建阶段成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/createstage.ui.class.php';

$stage = zendata('stage');
$stage->id->range('1-6');
$stage->name->range('需求, 设计, 开发, 测试, 发布, 总结评审');
$stage->percent->range('10,10,40,15,10,5');
$stage->type->range('request,design,dev,qa,release,review');
$stage->projectType->range('waterfall{6}');
$stage->createdBy->range('admin');
$stage->createdDate->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$stage->deleted->range('0');
$stage->gen(6);

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('waterfall');
$project->type->range('project');
$project->auth->range('extend');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('瀑布项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->begin->range('(-72w)-(-71w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+72w)-(+73w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(1);

zendata('projectspec')->gen(0);

$tester = new createstageTester();
$tester->login();

$waterfall = array(
    array('name_0' => '', 'begin_0' => date('Y-m-d'), 'end_0' => date('Y-m-d', strtotime('+30 days'))),
    array('begin_0' => '', 'end_0' => date('Y-m-d', strtotime('+30 days'))),
    array('begin_0' => date('Y-m-d'), 'end_0' => ''),
    array('begin_0' => '2024-10-24', 'end_0' => '2024-10-23'),
    array('name_0' => '需求阶段', 'begin_0' => '2024-10-24', 'end_0' => '2024-10-30'),
);

r($tester->createStage($waterfall['0'])) && p('message,status') && e('阶段名称不能为空提示信息正确,SUCCESS'); //校验阶段名称不能为空
r($tester->createStage($waterfall['1'])) && p('message,status') && e('计划开始日期不能为空提示信息正确,SUCCESS'); //校验计划开始必填
r($tester->createStage($waterfall['2'])) && p('message,status') && e('计划结束日期不能为空提示信息正确,SUCCESS'); //校验计划完成必填
r($tester->createStage($waterfall['3'])) && p('message,status') && e('计划开始不能大于计划完成提示信息正确,SUCCESS'); //校验计划完成必须大于计划开始
r($tester->createStage($waterfall['4'])) && p('message,status') && e('创建阶段成功,SUCCESS'); //创建需求阶段

$tester->closeBrowser();
