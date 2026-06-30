#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0{5}');
$project->name->prefix("项目")->range('1-5');
$project->type->range("project{5}");
$project->grade->range("1{5}");
$project->status->range("wait,doing,done,suspended,closed");
$project->openedBy->range("admin,user1,testuser,user1,testuser");
$project->PM->range("admin,user1,testuser,user1,testuser");
$project->vision->range("rnd{5}");
$project->acl->range("open{5}");
$project->whitelist->range("admin{5}");
$project->deleted->range("0{5}");
$project->order->range("1-5");
$project->gen(5);

$team = zenData('team');
$team->id->range('1-5');
$team->root->range('1-5');
$team->type->range('project{5}');
$team->account->range('admin{5}');
$team->role->range('研发{5}');
$team->gen(5);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,testuser,user2,user3');
$user->realname->range('管理员,用户1,测试用户,用户2,用户3');
$user->gen(5);

/**

title=测试 projectModel::getInvolvedListByCurrentUser();
timeout=0
cid=17828

- 执行projectTest模块的getInvolvedListByCurrentUserTest方法，参数是't1.*'  @10
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法，参数是't1.id, t1.name'
 - 第0条的id属性 @1
 - 第0条的name属性 @项目1
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法 第0条的name属性 @项目1
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法  @2
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法  @2

*/

su('admin');

$projectTest = new projectModelTest();
$adminProjects = array_values($projectTest->getInvolvedListByCurrentUserTest());
r(count($projectTest->getInvolvedListByCurrentUserTest('t1.*'))) && p() && e('5');
r($adminProjects) && p('0:id,name') && e('1,项目1');
r($adminProjects) && p('0:name') && e('项目1');

su('user1');
global $app;
$app->user->view->projects = '2,4';
r(count($projectTest->getInvolvedListByCurrentUserTest())) && p() && e('2');

su('testuser');
$app->user->view->projects = '3,5';
r(count($projectTest->getInvolvedListByCurrentUserTest())) && p() && e('2');
