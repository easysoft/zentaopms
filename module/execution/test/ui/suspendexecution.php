#!/usr/bin/env php
<?php

/**

title=挂起执行
timeout=0
cid=1

- 挂起未开始的执行
 - 最终测试状态 @SUCCESS
 - 测试结果 @挂起执行成功
- 挂起进行中的执行
 - 最终测试状态 @SUCCESS
 - 测试结果 @挂起执行成功

*/

chdir(__DIR__);
include '../lib/ui/suspendexecution.ui.class.php';

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
$project->status->range('doing, wait, doing');
$project->gen(3);

$tester = new suspendExecutionTester();
$tester->login();

r($tester->suspend('2')) && p('status,message') && e('SUCCESS,挂起执行成功'); //挂起未开始的执行
r($tester->suspend('3')) && p('status,message') && e('SUCCESS,挂起执行成功'); //挂起进行中的执行
$tester->closeBrowser();
