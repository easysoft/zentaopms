#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

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

/**

title=测试 projectModel::fetchProjectInfo();
timeout=0
cid=17904

- 获取ID等于2的项目
 - 属性id @2
 - 属性project @2
 - 属性name @项目2
 - 属性status @wait
 - 属性code @project2
 - 属性type @project
- 获取不存在的项目属性code @0
- 获取字符串ID的项目属性code @($projectID) must be of type int

*/

global $tester;
$projectModel = $tester->loadModel('project');
r($projectModel->fetchProjectInfo(2))      && p('id,project,name,status,code,type') && e('2,2,项目2,wait,project2,project');                 //获取ID等于2的项目
r($projectModel->fetchProjectInfo(1))      && p('code')      && e('0');                                //获取不存在的项目
