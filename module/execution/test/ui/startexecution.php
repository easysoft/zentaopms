#!/usr/bin/env php
<?php

/**

title=开始执行
timeout=0
cid=1

- 执行tester模块的startWithGreaterDate方法，参数是$realBegan[0]▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @开始执行表单页提示信息正确
- 执行tester模块的start方法，参数是$realBegan[1]▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @开始执行成功

*/

chdir(__DIR__);
include '../lib/ui/startexecution.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1');
$project->model->range('scrum, []');
$project->type->range('project, sprint');
$project->auth->range('extend, []');
$project->storyType->range('story, []');
$project->parent->range('0, 1');
$project->path->range('`,1,`, `,1,2,`');
$project->grade->range('1');
$project->name->range('项目, 执行');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('wait');
$project->gen(2);

$tester = new startExecutionTester();
$tester->login();

$realBegan = array(date('Y-m-d', strtotime('+20 days')), date('Y-m-d'));

r($tester->startWithGreaterDate($realBegan[0])) && p('status,message') && e('SUCCESS,开始执行表单页提示信息正确');
r($tester->start($realBegan[1]))                && p('status,message') && e('SUCCESS,开始执行成功');
$tester->closeBrowser();
