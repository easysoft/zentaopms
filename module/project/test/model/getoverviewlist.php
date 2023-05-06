#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

function initData()
{
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
}

su('admin');

/**

title=测试 projectModel::getOverviewList;
cid=1

*/

global $tester;
$tester->loadModel('project');

$byStatus1 = $tester->project->getOverviewList('byStatus', 'wait');
$byStatus2 = $tester->project->getOverviewList('byStatus', 'undone');

$byId1 = $tester->project->getOverviewList('byid', '11');
$byId2 = $tester->project->getOverviewList('byid', '10000');

$byOrder1 = $tester->project->getOverviewList('byStatus', 'all', 'id_asc');
$byOrder2 = $tester->project->getOverviewList('byStatus', 'all', 'name_desc');

r(count($byStatus1))  && p()          && e('3');         // 获取未开始的项目数量
r(count($byStatus2))  && p()          && e('7');         // 获取状态不为done和closed的项目数量
r(count($byId1))      && p()          && e('1');         // 根据项目ID获取项目
r(count($byId2))      && p()          && e('0');         // 获取不存在的项目
r(current($byOrder1)) && p('id,name') && e('11,项目11'); // 按照ID正序获取项目列表,查看排第一个的项目详情
r(current($byOrder2)) && p('id,name') && e('19,项目19'); // 按照项目名称倒序获取项目列表,查看排第一个的项目详情
