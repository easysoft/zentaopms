#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$project = zenData('project');
$project->id->range('1-10');
$project->project->range('0{10}');
$project->name->prefix("项目")->range('1-10');
$project->type->range("project{10}");
$project->grade->range("1{10}");
$project->status->range("wait,doing,done,suspended,closed");
$project->openedBy->range("admin{2},user1{2},user2{2},user3{2},testuser{2}");
$project->PM->range("admin{2},user1{2},user2{2},user3{2},testuser{2}");
$project->vision->range("rnd{10}");
$project->acl->range("open{8},private{2}");
$project->whitelist->range("admin{2},user1{2},user2{2},user3{2},testuser{2}");
$project->deleted->range("0{10}");
$project->order->range("1-10");
$project->gen(10);

$team = zenData('team');
$team->id->range('1-15');
$team->root->range('1{3},2{3},3{3},4{3},5{3}');
$team->type->range('project{15}');
$team->account->range('admin{3},user1{3},user2{3},user3{3},testuser{3}');
$team->gen(15);

$stakeholder = zenData('stakeholder');
$stakeholder->id->range('1-10');
$stakeholder->objectID->range('1-10');
$stakeholder->objectType->range('project{10}');
$stakeholder->user->range('admin{2},user1{2},user2{2},user3{2},testuser{2}');
$stakeholder->deleted->range('0{10}');
$stakeholder->gen(10);

/**

title=测试 projectModel::getInvolvedListByCurrentUser();
timeout=0
cid=0

- 测试步骤1：admin用户查询所有字段的参与项目列表 >> 期望返回项目数量为10
- 测试步骤2：admin用户查询指定字段的参与项目列表 >> 期望返回指定项目ID和名称
- 测试步骤3：admin用户查询默认字段的参与项目列表 >> 期望返回项目名称
- 测试步骤4：user1用户查询参与的项目列表 >> 期望返回项目数量为2
- 测试步骤5：testuser用户查询参与的项目列表 >> 期望返回项目数量为2

*/

su('admin');

include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

$projectTest = new projectTest();
r(count($projectTest->getInvolvedListByCurrentUserTest('t1.*'))) && p() && e('10');
r($projectTest->getInvolvedListByCurrentUserTest('t1.id,t1.name')) && p('1:id,1:name') && e('1,项目1');
r($projectTest->getInvolvedListByCurrentUserTest()) && p('1:name') && e('项目1');
su('user1');
r(count($projectTest->getInvolvedListByCurrentUserTest())) && p() && e('2');
su('testuser');
r(count($projectTest->getInvolvedListByCurrentUserTest())) && p() && e('2');