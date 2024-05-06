#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

function initData()
{
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
    $project->status->range("wait");
    $project->desc->range("[]");
    $project->budget->range("100000,200000");
    $project->budgetUnit->range("CNY");
    $project->percent->range("0-0");

    $project->gen(4);
}

/**

title=测试 projectModel::getByID;
timeout=0
cid=1

- 执行project模块的getByID方法，参数是2, 'project
 - 属性code @project2
 - 属性type @project

- 执行project模块的getByID方法，参数是1, 'project,属性code @0


*/

initData();

$projectTester = new Project();
r($projectTester->testGetByID(2)) && p('code,type') && e('project2,project');                 //获取ID等于11的项目
r($projectTester->testGetByID(1))  && p('code') && e('0');                                    //获取不存在的项目
r($projectTester->testGetByID('aaa'))  && p('code') && e('($projectID) must be of type int'); //获取字符串ID的项目
