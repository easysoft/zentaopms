#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

/**

title=测试 projectModel->doActivate();
timeout=0
cid=17897

- 更新不存在的项目 @1
- 更新项目2 @1
- 更新项目3 @1
- 项目2修改前的状态属性status @closed
- 项目2修改后的状态属性status @doing
- 项目3修改前的状态属性status @suspended
- 项目3修改后的状态属性status @doing

*/

$project = zenData('project');
$project->id->range('2-5');
$project->project->range('2-5');
$project->name->prefix("项目")->range('2-5');
$project->code->prefix("project")->range('2-5');
$project->model->range("scrum");
$project->auth->range("[]");
$project->path->range("[]");
$project->type->range("project");
$project->grade->range("1");
$project->days->range("1");
$project->status->range("closed, suspended");
$project->desc->range("[]");
$project->budget->range("100000,200000");
$project->budgetUnit->range("CNY");
$project->percent->range("0-0");

$project->gen(2);

global $tester;
$tester->loadModel('project');

$data = new stdClass();
$data->status       = 'doing';
$data->begin        = '2022-10-10';
$data->end          = '2022-10-10';
$data->comment      = '这是一条备注';
$data->readjustTime = 1;
$data->readjustTask = 1;

$oldProject2 = $tester->project->fetchByID('2');
$oldProject3 = $tester->project->fetchByID('3');

r($tester->project->doActivate(1, $data))  && p() && e('1'); // 更新不存在的项目
r($tester->project->doActivate(2, $data))  && p() && e('1'); // 更新项目2
r($tester->project->doActivate(3, $data))  && p() && e('1'); // 更新项目3


$newProject2 = $tester->project->fetchByID('2');
$newProject3 = $tester->project->fetchByID('3');

r($oldProject2)  && p('status') && e('closed');    // 项目2修改前的状态
r($newProject2)  && p('status') && e('doing');     // 项目2修改后的状态
r($oldProject3)  && p('status') && e('suspended'); // 项目3修改前的状态
r($newProject3)  && p('status') && e('doing');     // 项目3修改后的状态