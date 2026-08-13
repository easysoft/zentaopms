#!/usr/bin/env php
<?php

/**

title=测试 repoModel::setHideMenu();
timeout=0
cid=18103

- 步骤1：execution环境下有代码库时返回对象ID @101
- 步骤2：execution环境下无代码库时返回对象ID @102
- 步骤3：project环境下有代码库时返回对象ID @103
- 步骤4：waterfall环境下有代码库时返回对象ID @104
- 步骤5：execution环境下切换其他代码库时返回对象ID @105

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$entry = zenData('entry');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->gen(1);

$space = zenData('ops_space');
$space->id->range('1-5');
$space->name->range('space1,space2,space3,space4,space5');
$space->code->range('space1,space2,space3,space4,space5');
$space->acl->range('open{5}');
$space->auth->range('extend{5}');
$space->createdBy->range('admin{5}');
$space->deleted->range('0{5}');
$space->gen(5);

$spaceUser = zenData('ops_spaceuser');
$spaceUser->space->range('1-5');
$spaceUser->account->range('admin{5}');
$spaceUser->role->range('manager{5}');
$spaceUser->gen(5);

$repo = zenData('ops_repo');
$repo->id->range('1-5');
$repo->spaceID->range('1{5}');
$repo->product->range('1-5');
$repo->name->range('repo1,repo2,repo3,repo4,repo5');
$repo->scmType->range('git{5}');
$repo->gitUID->range('sethidemenu-gituid-1,sethidemenu-gituid-2,sethidemenu-gituid-3,sethidemenu-gituid-4,sethidemenu-gituid-5');
$repo->acl->range('open{5}');
$repo->status->range('active{5}');
$repo->deleted->range('0{5}');
$repo->gen(5);

$project = zenData('project');
$project->id->range('101-105');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project{5}');
$project->status->range('doing{5}');
$project->deleted->range('0{5}');
$project->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('101-105');
$projectProduct->product->range('1-5');
$projectProduct->branch->range('0{5}');
$projectProduct->plan->range('{5}');
$projectProduct->roadmap->range('{5}');
$projectProduct->gen(5);

su('admin');

$repoTest = new repoModelTest();

$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('execution', 101)) && p() && e('101');

$tester->session->set('repoID', 0);
r($repoTest->setHideMenuTest('execution', 102)) && p() && e('102');

$tester->session->set('repoID', 2);
r($repoTest->setHideMenuTest('project', 103)) && p() && e('103');

$tester->session->set('repoID', 3);
r($repoTest->setHideMenuTest('waterfall', 104)) && p() && e('104');

$tester->session->set('repoID', 5);
r($repoTest->setHideMenuTest('execution', 105)) && p() && e('105');
