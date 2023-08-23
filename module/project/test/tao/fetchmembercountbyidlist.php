#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

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
zdTable('user')->gen(10);

/**

title=测试 projectModel::fetchProjectList();
timeout=0
cid=1


*/

$noneIDList  = array();
$wrongIDList = array('1',  '2');
$realIDList  = array('11', '12');

global $tester;
$projectTester = $tester->loadModel('project');
r($projectTester->fetchMemberCountByIdList($noneIDList))  && p()     && e('0'); // 查询没有项目ID的情况
r($projectTester->fetchMemberCountByIdList($wrongIDList)) && p()     && e('0'); // 查询错误ID的情况
r($projectTester->fetchMemberCountByIdList($realIDList))  && p('11') && e('1'); // 查询正常ID的情况
