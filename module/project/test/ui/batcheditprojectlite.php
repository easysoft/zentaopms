#!/usr/bin/env php
<?php

/**

title=运营界面批量编辑项目
timeout=0
cid=23

- 批量编辑项目缺少项目名称
 - 测试结果 @项目名称必填提示信息正确
 - 最终测试状态 @SUCCESS
- 批量编辑项目计划完成时间小于计划开始时间
 - 测试结果 @计划完成校验提示信息正确
 - 最终测试状态 @SUCCESS
- 批量编辑项目名称为已有名称
 - 测试结果 @项目名称唯一提示信息正确
 - 最终测试状态 @SUCCESS
- 批量编辑项目名称
 - 测试结果 @批量编辑项目成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/batcheditprojectlite.ui.class.php';

$project = zenData('project');
$project->id->range('1-2');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->auth->range('extend');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('运营项目1, 运营项目2');
$project->hasProduct->range('0');
$project->status->range('doing');
$project->begin->range('(-2w)-(-1w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2w)-(+3w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->vision->range('lite');
$project->gen(2);

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('影子产品1, 影子产品2');
$product->shadow->range('1');
$product->type->range('normal');
$product->vision->range('lite');
$product->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-2');
$projectProduct->product->range('1{1}, 2{1}');
$projectProduct->gen(2);

$tester = new batchEditProjectLiteTester();
$tester->login();

$project = array(
    array('name' => '', 'begin' => date('Y-m-d', strtotime('-30 days')), 'end' => date('Y-m-d', strtotime('+30 days'))),
    array('name' => '运营项目1', 'begin' => date('Y-m-d'), 'end' => date('Y-m-d', strtotime('-1 day'))),
    array('name' => '运营项目2', 'begin' => date('Y-m-d', strtotime('-1 month')), 'end' => date('Y-m-d', strtotime('+1 month'))),
    array('name' => '运营项目a' . time(), 'begin' => date('Y-m-d', strtotime('-30 days')), 'end' => date('Y-m-d', strtotime('+30 days')), 'acl' => '公开'),
);

r($tester->checkInput($project['0'])) && p('message,status') && e('项目名称必填提示信息正确,SUCCESS'); // 批量编辑项目缺少项目名称
r($tester->checkInput($project['1'])) && p('message,status') && e('计划完成校验提示信息正确,SUCCESS'); // 批量编辑项目计划完成时间小于计划开始时间
r($tester->checkInput($project['2'])) && p('message,status') && e('项目名称唯一提示信息正确,SUCCESS'); // 批量编辑项目名称为已有名称
r($tester->checkInput($project['3'])) && p('message,status') && e('批量编辑项目成功,SUCCESS');         // 批量编辑项目名称

$tester->closeBrowser();
