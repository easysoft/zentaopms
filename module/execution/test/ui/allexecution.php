#!/usr/bin/env php
<?php

/**

title=批量编辑执行状态
timeout=0
cid=1
*/

chdir(__DIR__);
include '../lib/allexecution.ui.class.php';

$project = zendata('project');
$project->id->range('1-2');
$project->project->range('0');
$project->model->range('scrum{2}');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->name->range('项目1, 项目2');
$project->path->range('`,1,`, `,2,`');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(2);

$execution = zenData('project');
$execution->id->range('5-9');
$execution->project->range('1{3}, 2{2}');
$execution->type->range('sprint');
$execution->attribute->range('[]');
$execution->auth->range('[]');
$execution->parent->range('1{3}, 2{2}');
$execution->grade->range('1');
$execution->name->range('项目1迭代1, 项目1迭代2, 项目1迭代3, 项目2迭代1, 项目2迭代2');
$execution->path->range('`,1,5,`, `,1,6,`, `,1,7,`, `,2,8,`, `,2,9,`');
$execution->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->acl->range('open');
$execution->status->range('wait{2}, doing, suspended, closed');
$execution->gen(5, false);

$execution = zenData('project');
$execution->id->range('10');
$execution->project->range('1');
$execution->type->range('sprint');
$execution->attribute->range('[]');
$execution->auth->range('[]');
$execution->parent->range('1');
$execution->grade->range('1');
$execution->name->range('项目1迭代4');
$execution->path->range('`,1,10,`');
$execution->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(-5D)-(-3D):1D')->type('timestamp')->format('YY/MM/DD');
$execution->acl->range('open');
$execution->status->range('wait');
$execution->gen(1, false);

$tester = new allExecutionTester();
$tester->login();

/* 检查标签下显示条数 */
r($tester->checkTab('all', '6'))       && p('status,message') && e('SUCCESS,all标签下显示条数正确');
r($tester->checkTab('undone', '5'))    && p('status,message') && e('SUCCESS,undone标签下显示条数正确');
r($tester->checkTab('wait', '3'))      && p('status,message') && e('SUCCESS,wait标签下显示条数正确');
r($tester->checkTab('doing', '1'))     && p('status,message') && e('SUCCESS,doing标签下显示条数正确');
r($tester->checkTab('suspended', '1')) && p('status,message') && e('SUCCESS,suspended标签下显示条数正确');
r($tester->checkTab('delayed', '1'))   && p('status,message') && e('SUCCESS,delayed标签下显示条数正确');
r($tester->checkTab('closed', '1'))    && p('status,message') && e('SUCCESS,closed标签下显示条数正确');
/* 批量操作执行状态 */
r($tester->changeStatus('wait'))      && p('status,message') && e('SUCCESS,批量操作执行状态为wait成功');
r($tester->changeStatus('doing'))     && p('status,message') && e('SUCCESS,批量操作执行状态为doing成功');
r($tester->changeStatus('suspended')) && p('status,message') && e('SUCCESS,批量操作执行状态为suspended成功');
r($tester->changeStatus('closed'))    && p('status,message') && e('SUCCESS,批量操作执行状态为closed成功');
$tester->closeBrowser();
