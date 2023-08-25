#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectModel->activate();
timeout=0
cid=1

*/

$project = zdTable('project');
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
$data->status       = 'doing';
$data->comment      = '这是一条备注';
$data->readjustTime = 1;
$data->readjustTask = 1;

r($tester->project->doActivate(1, $data))  && p() && e('1'); // 更新不存在的项目
r($tester->project->doActivate(2, $data))  && p() && e('1'); // 更新项目2
r($tester->project->doActivate(3, $data))  && p() && e('1'); // 更新项目3
