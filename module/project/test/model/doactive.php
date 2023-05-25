#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectModel->activate();
cid=1
pid=1


*/

function initData()
{
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
}

initData();

global $tester;
$tester->loadModel('project');
$project = new Project();
$data    = new stdClass();

$data->status       = 'doing';
$data->begin        = '2022-10-10';
$data->end          = '2022-10-10';
$data->status       = 'doing';
$data->comment      = 'fgasgqasfdgasfgasg';
$data->readjustTime = 1;
$data->readjustTask = 1;

r(strlen($tester->project->doActivate(2, $data)))  && p() && e(true);   // 判断是否更新无报错 true
r(strlen($tester->project->doActivate(3, $data)))  && p() && e(true);   // 判断是否更新无报错 true
