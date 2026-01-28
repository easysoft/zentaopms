#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/tao.class.php';
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
zenData('user')->gen(10);

/**

title=测试 projectModel::fetchMemberCountByIdList();
timeout=0
cid=17903

- 查询没有项目ID的情况 @0
- 查询错误ID的情况 @0
- 查询正常ID的情况
 - 属性11 @1
 - 属性12 @1
 - 属性13 @1
- 查询不存在ID的情况 @0

*/

$noneIDList  = array();
$wrongIDList = array('1',  '2');
$realIDList  = array('11', '12', '13', '1');

global $tester;
$projectTester = $tester->loadModel('project');
r($projectTester->fetchMemberCountByIdList($noneIDList))  && p()     && e('0'); // 查询没有项目ID的情况
r($projectTester->fetchMemberCountByIdList($wrongIDList)) && p()     && e('0'); // 查询错误ID的情况

$memberCount = $projectTester->fetchMemberCountByIdList($realIDList);
r($memberCount)           && p('11,12,13') && e('1,1,1'); // 查询正常ID的情况
r(isset($memberCount[1])) && p()           && e('0');     // 查询不存在ID的情况