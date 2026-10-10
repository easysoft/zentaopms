#!/usr/bin/env php
<?php

/**

title=测试 repoModel::loadMaintainSpacePrivs();
timeout=0
cid=0

- 无代码库编辑权限的用户加载后仍无编辑权限 @0
- 无代码库删除权限的用户加载后仍无删除权限 @0
- 空间成员未获授权时空间权限缓存不含编辑权限 @0
- 有代码库编辑权限的用户加载后保留编辑权限 @1
- 有代码库删除权限的用户加载后保留删除权限 @1
- 空间组成员获得授权时空间权限缓存包含编辑权限 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(0);
zenData('ops_spaceuser')->gen(0);
zenData('ops_repouser')->gen(0);
zenData('ops_repo')->gen(0);
zenData('group')->gen(0);
zenData('grouppriv')->gen(0);
zenData('usergroup')->gen(0);

$spaceTable = zenData('ops_space');
$spaceTable->id->range('1');
$spaceTable->name->range('maintain-priv-space');
$spaceTable->code->range('maintainprivspace');
$spaceTable->acl->range('open');
$spaceTable->auth->range('extend');
$spaceTable->deleted->range('0');
$spaceTable->gen(1);

$spaceUserTable = zenData('ops_spaceuser');
$spaceUserTable->space->range('1');
$spaceUserTable->account->range('admin');
$spaceUserTable->role->range('manager');
$spaceUserTable->gen(1);

$repoTable = zenData('ops_repo');
$repoTable->id->range('1');
$repoTable->spaceID->range('1');
$repoTable->product->range('1');
$repoTable->name->range('maintain-priv-repo');
$repoTable->scmType->range('git');
$repoTable->gitUID->range('uid1');
$repoTable->providerID->range('0');
$repoTable->mirror->range('0');
$repoTable->acl->range('open');
$repoTable->status->range('active');
$repoTable->deleted->range('0');
$repoTable->gen(1);

/* 组1：全局代码库编辑、删除权限；组2：空间1的代码库编辑权限。 */
$group = zenData('group');
$group->id->range('1,2');
$group->name->range('repo-manage-group,space-repo-group');
$group->project->range('0{2}');
$group->devopsSpace->range('0,1');
$group->vision->range($tester->config->vision . '{2}');
$group->gen(2);

$groupPriv = zenData('grouppriv');
$groupPriv->group->range('1,1,2');
$groupPriv->module->range('repo{3}');
$groupPriv->method->range('edit,delete,edit');
$groupPriv->gen(3);

$userGroup = zenData('usergroup');
$userGroup->account->range('user2,user3');
$userGroup->group->range('1,2');
$userGroup->project->range('0{2}');
$userGroup->gen(2);

su('admin');

$repoTest = new repoModelTest();
$repos    = $repoTest->instance->getByIDList(array(1));

su('user1');
r($repoTest->loadMaintainSpacePrivsHasPrivTest($repos, 'repo', 'edit'))           && p() && e('0'); // 无代码库编辑权限的用户加载后仍无编辑权限
r($repoTest->loadMaintainSpacePrivsHasPrivTest($repos, 'repo', 'delete'))         && p() && e('0'); // 无代码库删除权限的用户加载后仍无删除权限
r($repoTest->loadMaintainSpacePrivsCacheTest($repos, 1, 'repo', 'edit'))          && p() && e('0'); // 空间成员未获授权时空间权限缓存不含编辑权限

su('user2');
r($repoTest->loadMaintainSpacePrivsHasPrivTest($repos, 'repo', 'edit'))           && p() && e('1'); // 有代码库编辑权限的用户加载后保留编辑权限
r($repoTest->loadMaintainSpacePrivsHasPrivTest($repos, 'repo', 'delete'))         && p() && e('1'); // 有代码库删除权限的用户加载后保留删除权限

su('user3');
r($repoTest->loadMaintainSpacePrivsCacheTest($repos, 1, 'repo', 'edit'))          && p() && e('1'); // 空间组成员获得授权时空间权限缓存包含编辑权限
