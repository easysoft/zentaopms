#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

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

/**

title=测试 projectModel::getOverviewList($status, $projectID, $orderBy);
timeout=0
cid=1

*/

global $tester;
$projectTester = $tester->loadModel('project');

r(count($projectTester->getOverviewList('wait')))   && p() && e('3'); // 获取未开始的项目数量
r(count($projectTester->getOverviewList('undone'))) && p() && e('7'); // 获取状态不为done和closed的项目数量

r(count($projectTester->getOverviewList('', 11)))    && p() && e('1'); // 根据项目ID获取项目
r(count($projectTester->getOverviewList('', 10000))) && p() && e('0'); // 获取不存在的项目

r(count($projectTester->getOverviewList('doing', 18))) && p() && e('1'); // 根据 不匹配的项目ID和状态 获取项目数量
r(count($projectTester->getOverviewList('doing', 12))) && p() && e('1'); // 根据 匹配的项目ID和状态 获取项目数量

r(current($projectTester->getOverviewList('all', 0, 'id_asc')))    && p('id,name') && e('11,项目11'); // 按照ID正序获取项目列表,查看排第一个的项目详情
r(current($projectTester->getOverviewList('all', 0, 'name_desc'))) && p('id,name') && e('19,项目19'); // 按照项目名称倒序获取项目列表,查看排第一个的项目详情
