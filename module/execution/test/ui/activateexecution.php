#!/usr/bin/env php
<?php

/**

title=激活执行
timeout=0
cid=1

- 计划完成日期为空，激活失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @激活执行表单页提示信息正确
- 计划完成日期小于计划开始日期，激活失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @激活执行表单页提示信息正确
- 计划完成日期大于项目的计划完成日期，激活失败
 - 最终测试状态 @SUCCESS
 - 测试结果 @激活执行表单页提示信息正确
- 成功激活挂起的执行
 - 最终测试状态 @SUCCESS
 - 测试结果 @激活执行成功
- 成功激活已关闭的执行
 - 最终测试状态 @SUCCESS
 - 测试结果 @激活执行成功

*/

chdir(__DIR__);
include '../lib/ui/activateexecution.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{2}');
$project->model->range('scrum, []{2}');
$project->type->range('project, sprint{2}');
$project->auth->range('extend, []{2}');
$project->storyType->range('story, []{2}');
$project->parent->range('0, 1{2}');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`');
$project->grade->range('1');
$project->name->range('项目, 挂起的执行, 已关闭的执行');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing, suspended, closed');
$project->gen(3);

$tester = new activateExecutionTester();
$tester->login();

$end = array(date('Y-m-d', strtotime('+1 days')), '', date('Y-m-d', strtotime('-10 months')), date('Y-m-d', strtotime('+10 months')));

r($tester->activateWithLessEnd($end[1], '2'))    && p('status,message') && e('SUCCESS,激活执行表单页提示信息正确'); //计划完成日期为空，激活失败
r($tester->activateWithLessEnd($end[2], '3'))    && p('status,message') && e('SUCCESS,激活执行表单页提示信息正确'); //计划完成日期小于计划开始日期，激活失败
r($tester->activateWithGreaterEnd($end[3], '2')) && p('status,message') && e('SUCCESS,激活执行表单页提示信息正确'); //计划完成日期大于项目的计划完成日期，激活失败
r($tester->activate($end[0], '2'))               && p('status,message') && e('SUCCESS,激活执行成功');               //成功激活挂起的执行
r($tester->activate($end[0], '3'))               && p('status,message') && e('SUCCESS,激活执行成功');               //成功激活已关闭的执行
$tester->closeBrowser();
