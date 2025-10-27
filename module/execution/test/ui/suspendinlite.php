#!/usr/bin/env php
<?php

/**

title=运营界面挂起看板
timeout=0
cid=1

- 挂起未开始的看板
 - 最终测试状态 @SUCCESS
 - 测试结果 @挂起看板成功
- 挂起进行中的看板
 - 最终测试状态 @SUCCESS
 - 测试结果 @挂起看板成功

*/

chdir(__DIR__);
include '../lib/ui/suspendinlite.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{2}');
$project->model->range('kanban, []{2}');
$project->type->range('project, kanban{2}');
$project->auth->range('[]');
$project->storyType->range('[]{2}');
$project->parent->range('0, 1{2}');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`');
$project->grade->range('1');
$project->name->range('项目, 未开始的看板, 已关闭的看板');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing, wait, doing');
$project->vision->range('lite');
$project->gen(3);

$tester = new suspendExecutionTester();
$tester->login();

r($tester->suspend('2')) && p('status,message') && e('SUCCESS,挂起看板成功'); //挂起未开始的看板
r($tester->suspend('3')) && p('status,message') && e('SUCCESS,挂起看板成功'); //挂起进行中的看板
$tester->closeBrowser();
