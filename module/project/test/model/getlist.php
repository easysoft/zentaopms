#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
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

zenData('team')->gen(10);

$stakeholder = zenData('stakeholder');
$stakeholder->id->range('1-9');
$stakeholder->objectID->range('11-19');
$stakeholder->objectType->range('program,project');
$stakeholder->user->range("admin");
$stakeholder->type->range("inside");
$stakeholder->from->range("[]");
$stakeholder->createdBy->range("admin");
$stakeholder->createdDate->range("`2023-05-01 10:00:10`");
$stakeholder->gen(9);

/**

title=测试 projectModel::fetchProjectList();
timeout=0
cid=17829

- 查询状态为空的项目 @0
- 获取所有项目数量 @9
- 查询未完成的第一个项目的code第11条的code属性 @project11
- 获取我参与的一个项目的项目名第12条的name属性 @项目12
- 获取错误类型的项目 @0

*/

$statusList = array('', 'all', 'undone', 'unclosed', 'error');

$projectTester = new projectModelTest();
r($projectTester->getListTest($statusList[0]))        && p()          && e('0');         // 查询状态为空的项目
r(count($projectTester->getListTest($statusList[1]))) && p()          && e('9');         // 获取所有项目数量
r($projectTester->getListTest($statusList[2]))        && p('11:code') && e('project11'); // 查询未完成的第一个项目的code
r($projectTester->getListTest($statusList[3], true))  && p('12:name') && e('项目12');    // 获取我参与的一个项目的项目名
r($projectTester->getListTest($statusList[4]))        && p()          && e('0');         // 获取错误类型的项目
