#!/usr/bin/env php
<?php

/**

title=敏捷项目迭代列表标签检查
timeout=0
cid=73

- 检查全部标签数量
 - 测试结果 @全部标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查未完成标签数量
 - 测试结果 @未完成标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查未开始标签数量
 - 测试结果 @未开始标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查进行中标签数量
 - 测试结果 @进行中标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查已挂起标签数量
 - 测试结果 @已挂起标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查已延期标签数量
 - 测试结果 @已延期标签下条数显示正确
 - 最终测试状态 @SUCCESS
- 检查已关闭标签数量
 - 测试结果 @已关闭标签下条数显示正确
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/execution.ui.class.php';

$project = zenData('project');
$project->id->range('1-16');
$project->project->range('0, 1{15}');
$project->model->range('scrum, []{15}');
$project->type->range('project, sprint{15}');
$project->auth->range('[]');
$project->storytype->range('[]');
$project->parent->range('0, 1{15}');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`, `,1,5,`, `,1,6,`, `,1,7,`, `,1,8,`, `,1,9,`, `,1,10,`, `,1,11,`, `,1,12,`, `,1,13,`, `,1,14,`, `,1,15,`');
$project->grade->range('1');
$project->name->range('敏捷项目1, 迭代1, 迭代2, 迭代3, 迭代4, 迭代5, 迭代6, 迭代7, 迭代8, 迭代9, 迭代10, 迭代11, 迭代12, 迭代13, 迭代14, 迭代15');
$project->hasProduct->range('0');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(-11D)-(+2M):2D')->type('timestamp')->format('YY/MM/DD');
$project->status->range('wait{8}, doing{4}, suspended{3}, closed{1}');
$project->acl->range('open');
$project->vision->range('rnd');
$project->gen(16);

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('1');
$product->bind->range('1');
$product->type->range('normal');
$product->gen(1);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-16');
$projectProduct->product->range('1');
$projectProduct->gen(16);

$tester = new executionTester();
$tester->login();

r($tester->checkTab('all', '15'))      && p('message,status') && e('全部标签下条数显示正确,SUCCESS');    // 检查全部标签数量
r($tester->checkTab('undone', '14'))   && p('message,status') && e('未完成标签下条数显示正确,SUCCESS');  // 检查未完成标签数量
r($tester->checkTab('wait', '7'))      && p('message,status') && e('未开始标签下条数显示正确,SUCCESS');  // 检查未开始标签数量
r($tester->checkTab('doing', '4'))     && p('message,status') && e('进行中标签下条数显示正确,SUCCESS');  // 检查进行中标签数量
r($tester->checkTab('suspended', '3')) && p('message,status') && e('已挂起标签下条数显示正确,SUCCESS');  // 检查已挂起标签数量
r($tester->checkTab('delayed', '5'))   && p('message,status') && e('已延期标签下条数显示正确,SUCCESS');  // 检查已延期标签数量
r($tester->checkTab('closed', '1'))    && p('message,status') && e('已关闭标签下条数显示正确,SUCCESS');  // 检查已关闭标签数量

$tester->closeBrowser();
