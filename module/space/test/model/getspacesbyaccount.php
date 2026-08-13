#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getSpacesByAccount();
timeout=0
cid=0

- 用户为空时返回全部空间 @2
- admin用户在空间1中的角色 @manager
- 非管理员查询test1所属空间数量 @1
- 非管理员查询不存在用户返回空 @0
- 非管理员账号为空时返回全部空间 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

global $tester;
$vision = $tester->config->vision;

$spaceUser = zenData('ops_spaceuser');
$spaceUser->id->range('1-2');
$spaceUser->space->range('1,2');
$spaceUser->account->range('admin,test3');
$spaceUser->role->range('manager,manager');
$spaceUser->gen(2);

$group = zenData('group');
$group->id->range('1-2');
$group->name->range('space1-group,space2-group');
$group->project->range('0{2}');
$group->devopsSpace->range('1,2');
$group->vision->range("{$vision},{$vision}");
$group->gen(2);

$userGroup = zenData('usergroup');
$userGroup->account->range('admin,test1,test3');
$userGroup->group->range('1,1,2');
$userGroup->project->range('0{3}');
$userGroup->gen(3);

$repo = zenData('ops_repo');
$repo->id->range('1-2');
$repo->spaceID->range('1,2');
$repo->product->range('1,2');
$repo->name->range('repo-one,repo-two');
$repo->status->range('active{2}');
$repo->deleted->range('0{2}');
$repo->gen(2);

$repoUser = zenData('ops_repouser');
$repoUser->id->range('1-2');
$repoUser->repo->range('1,2');
$repoUser->account->range('test2,test3');
$repoUser->gen(2);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getSpacesByAccountCountTest(''))                              && p() && e('2');        // 用户为空时返回全部空间
r($spaceTester->getSpacesByAccountMemberFieldTest('admin', 1, 'admin', 'role')) && p() && e('manager'); // admin用户在空间1中的角色
$tester->app->user->admin = false;
r($spaceTester->getSpacesByAccountCountTest('test1'))                         && p() && e('1');        // 非管理员查询test1所属空间数量
r($spaceTester->getSpacesByAccountCountTest('notexist'))                      && p() && e('0');        // 非管理员查询不存在用户返回空
r($spaceTester->getSpacesByAccountCountTest(''))                              && p() && e('2');        // 非管理员账号为空时返回全部空间
