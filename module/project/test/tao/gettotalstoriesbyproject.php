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

zenData('story')->gen(50);
zenData('projectstory')->gen(10);

/**

title=测试 projectModel::getTotalStoriesByProject;
cid=17913

- 不传入项目ID列表，获取需求数量 @0
- 传入错误的ID列表，获取需求数量 @0
- 获取项目12的需求数量
 - 属性project @12
 - 属性allStories @2
 - 属性leftStories @2
 - 属性doneStories @0
- 检查项目13是否存在 @1
- 检查项目14是否存在 @1
- 检查不存在的项目1 @0

*/

global $tester;
$projectTester = $tester->loadModel('project');

$noneIdList      = array();
$notExistsIdList = array(1);
$realIdList      = array(12, 13, 14, 1);

r($projectTester->getTotalStoriesByProject($noneIdList))      && p() && e('0');   // 不传入项目ID列表，获取需求数量
r($projectTester->getTotalStoriesByProject($notExistsIdList)) && p() && e('0');   // 传入错误的ID列表，获取需求数量

$stories = $projectTester->getTotalStoriesByProject($realIdList);
r($stories[12]) && p('project,allStories,leftStories,doneStories') && e('12,2,2,0'); // 获取项目12的需求数量
r(isset($stories[13])) && p() && e('1'); // 检查项目13是否存在
r(isset($stories[14])) && p() && e('1'); // 检查项目14是否存在
r(isset($stories[1]))  && p() && e('0'); // 检查不存在的项目1
