#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

function initData()
{
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
}

initData();
su('admin');

/**

title=测试 projectModel::getProjectList($status, $projectID, $orderBy);
timeout=0
cid=17847

- 获取全部未开始的项目数量 @3

- 获取1个未开始的项目数量 @1

- 获取全部未完成的项目数量 @7

- 获取5个未完成的项目数量 @5

- 按照ID正序获取项目列表,查看排第一个的项目详情
- 属性id @11
- 属性name @项目11

- 按照项目序号倒序获取项目列表,查看排第一个的项目详情
- 属性id @19
- 属性name @项目19

*/

global $tester;
$projectTester = $tester->loadModel('project');

r(count($projectTester->getProjectList('wait', 'order_asc', 0, '')))   && p() && e('3');   // 获取全部未开始的项目数量
r(count($projectTester->getProjectList('wait', 'order_asc', 1, '')))     && p() && e('1'); // 获取1个未开始的项目数量
r(count($projectTester->getProjectList('undone', 'order_asc', 0, ''))) && p() && e('7');   // 获取全部未完成的项目数量
r(count($projectTester->getProjectList('undone', 'order_asc', 5, '')))   && p() && e('5'); // 获取5个未完成的项目数量
r(current($projectTester->getProjectList('all', 'id_asc', 2, '')))       && p('id,name') && e('11,项目11'); // 按照ID正序获取项目列表,查看排第一个的项目详情
r(current($projectTester->getProjectList('all', 'order_desc', 2, '')))   && p('id,name') && e('19,项目19'); // 按照项目序号倒序获取项目列表,查看排第一个的项目详情
