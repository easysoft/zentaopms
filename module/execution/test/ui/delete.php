#!/usr/bin/env php
<?php

/**

title=删除执行
timeout=0
cid=1

- 执行tester模块的delete方法▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @删除执行成功

 */

chdir(__DIR__);
include '../lib/ui/delete.ui.class.php';

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
$project->name->range('项目, 执行1, 执行2');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('wait');
$project->gen(3);

$tester = new deleteExecutionTester();
$tester->login();

r($tester->delete()) && p('status,message') && e('SUCCESS,删除执行成功');
$tester->closeBrowser();
