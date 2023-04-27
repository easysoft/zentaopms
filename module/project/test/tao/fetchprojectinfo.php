#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

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
    $project->status->range("wait");
    $project->desc->range("[]");
    $project->budget->range("100000,200000");
    $project->budgetUnit->range("CNY");
    $project->percent->range("0-0");

    $project->gen(4);
}

/**

title=测试 projectModel::fetchProjectInfo;
timeout=0
cid=1


*/

global $tester;
$tester->loadModel('project');

initData();

r($tester->project->fetchProjectInfo(2)) && p('code,type') && e('project2,project'); //获取ID等于11的项目
r($tester->project->fetchProjectInfo(1)) && p('code')      && e('0');                //获取不存在的项目
