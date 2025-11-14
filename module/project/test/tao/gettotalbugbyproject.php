#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

$project = zenData('project');
$project->id->range('11-19');
$project->project->range('11-19');
$project->name->prefix("项目")->range('11-19');
$project->code->prefix("project")->range('11-19');
$project->model->range("scrum");
$project->auth->range("[]");
$project->path->range("[]");
$project->type->range("project");
$project->grade->range("1");
$project->days->range("1");
$project->status->range("wait,doing,suspended,closed");
$project->desc->range("[]");
$project->budget->range("100000,200000");
$project->budgetUnit->range("CNY");
$project->percent->range("0-0");
$project->openedDate->range("`2023-05-01 10:00:10`");
$project->gen(9);

zenData('bug')->gen(50);

/**

title=测试 projectModel->getTotalBugByProject();
timeout=0
cid=17912

- 获取id为11的项目下bug数量第11条的allBugs属性 @3
- 获取id为11的项目下bug数量第12条的allBugs属性 @3
- 获取id为11的项目下bug数量第13条的allBugs属性 @3
- 获取id为27的项目下bug数量第27条的allBugs属性 @Error: Cannot get index 27.
- 获取项目为空时的bug数量 @0

*/

global $tester;
$tester->loadModel('project');

$projectIdList = array(11, 12, 13, 14, 15, 16, 27);
$result  = $tester->project->getTotalBugByProject($projectIdList);
$result2 = $tester->project->getTotalBugByProject(array());

r($result)  && p('11:allBugs') && e('3');                            //获取id为11的项目下bug数量
r($result)  && p('12:allBugs') && e('3');                            //获取id为11的项目下bug数量
r($result)  && p('13:allBugs') && e('3');                            //获取id为11的项目下bug数量
r($result)  && p('27:allBugs') && e('Error: Cannot get index 27.');  //获取id为27的项目下bug数量
r($result2) && p()             && e('0');                            //获取项目为空时的bug数量