#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectModel->activate();
cid=1
pid=1

激活id为2的项目
激活id为3的项目

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


$changes2 = $project->activate(2, $data);
$changes3 = $project->activate(3, $data);

r($changes2['0']) && p('field') && e('status');
r($changes2['0']) && p('old') && e('closed');
r($changes2['0']) && p('new') && e('doing');

r($changes3['0']) && p('field') && e('status');
r($changes3['0']) && p('old') && e('suspended');
r($changes3['0']) && p('new') && e('doing');
