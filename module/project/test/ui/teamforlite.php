#!/usr/bin/env php
<?php

/**

title=运营界面项目团队成员列表
timeout=0
cid=1

- 移除项目已有的团队成员测试结果 @项目团队成员移除成功

*/

chdir(__DIR__);
include '../lib/ui/teamforlite.ui.class.php';

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->auth->range('extend');
$project->storytype->range('');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('运营项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->begin->range('(-72w)-(-71w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+72w)-(+73w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(1);

$user = zenData('user');
$user->id->range('1-5');
$user->dept->range('1');
$user->account->range('admin, user1, user2, user3, user4');
$user->realname->range('admin, 用户1, 用户2, 用户3, 用户4');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(5);

$team = zenData('team');
$team->id->range('1-3');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin, user1, user2, user3, user4');
$team->days->range('7');
$team->hours->range('4');
$team->gen(3);

$tester = new teamTesterForLite();
$tester->login();

r($tester->removeMembers()) && p('message') && e('项目团队成员移除成功');   //移除项目已有的团队成员
$tester->closeBrowser();
