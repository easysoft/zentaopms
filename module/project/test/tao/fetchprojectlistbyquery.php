#!/usr/bin/env php
<?php

/**

title=测试 projectModel::fetchProjectListByQuery($status, $projectID, $orderBy);
cid=1

- 获取未开始的项目数量 @3
- 获取状态不为done和closed的项目数量 @7
- 根据项目ID获取项目 @1
- 获取不存在的项目 @0
- 根据 不匹配的项目ID和状态 获取项目数量 @0
- 根据 匹配的项目ID和状态 获取项目数量 @1
- 按照ID正序获取项目列表,查看排第一个的项目详情
 - 属性id @11
 - 属性name @项目11
- 按照项目名称倒序获取项目列表,查看排第一个的项目详情
 - 属性id @19
 - 属性name @项目19

*/

include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . "/project.class.php";

$project = zdTable('project');
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

zdTable('team')->gen(10);

$stakeholder = zdTable('stakeholder');
$stakeholder->id->range('1-9');
$stakeholder->objectID->range('11-19');
$stakeholder->objectType->range('program,project');
$stakeholder->user->range("admin");
$stakeholder->type->range("inside");
$stakeholder->from->range("[]");
$stakeholder->createdBy->range("admin");
$stakeholder->createdDate->range("`2023-05-01 10:00:10`");
$stakeholder->gen(9);

su('admin');

$projectTester = new Project();

r(count($projectTester->testFetchProjectListByQuery('wait')))   && p() && e('3'); // 获取未开始的项目数量
r(count($projectTester->testFetchProjectListByQuery('undone'))) && p() && e('7'); // 获取状态不为done和closed的项目数量

r(count($projectTester->testFetchProjectListByQuery('', 11)))    && p() && e('1'); // 根据项目ID获取项目
r(count($projectTester->testFetchProjectListByQuery('', 10000))) && p() && e('0'); // 获取不存在的项目

r(count($projectTester->testFetchProjectListByQuery('doing', 11))) && p() && e('0'); // 根据 不匹配的项目ID和状态 获取项目数量
r(count($projectTester->testFetchProjectListByQuery('doing', 12))) && p() && e('1'); // 根据 匹配的项目ID和状态 获取项目数量

r(current($projectTester->testFetchProjectListByQuery('all', 0, 'id_asc')))    && p('id,name') && e('11,项目11'); // 按照ID正序获取项目列表,查看排第一个的项目详情
r(current($projectTester->testFetchProjectListByQuery('all', 0, 'name_desc'))) && p('id,name') && e('19,项目19'); // 按照项目名称倒序获取项目列表,查看排第一个的项目详情
