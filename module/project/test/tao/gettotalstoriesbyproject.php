#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
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

zdTable('story')->gen(50);
zdTable('projectstory')->gen(10);

/**

title=测试 projectModel::getTotalStoriesByProject;
cid=1

*/

global $tester;
$projectTester = $tester->loadModel('project');

$noneIdList      = array();
$notExistsIdList = array(1);
$realIdList      = array(12, 13);

r($projectTester->getTotalStoriesByProject($noneIdList))      && p()                              && e('0');   // 不传入项目ID列表，获取需求数量
r($projectTester->getTotalStoriesByProject($notExistsIdList)) && p()                              && e('0');   // 传入错误的ID列表，获取需求数量
r($projectTester->getTotalStoriesByProject($realIdList))      && p('12:allStories;13:allStories') && e('2,2'); // 传入正确的ID列表，获取需求数量
