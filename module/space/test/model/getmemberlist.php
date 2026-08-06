#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getMemberList();
timeout=0
cid=0

- 获取所有空间成员列表中的空间数量 @2
- 获取空间1的成员数量 @3
- 获取空间1中的admin成员属性account @admin
- 获取空间2的成员数量 @1
- 验证空间1中admin的角色属性role @manager

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

r($spaceTester->getMemberListCountTest())           && p() && e('2');        // 获取所有空间成员列表中的空间数量
r($spaceTester->getMemberListBySpaceCountTest(1))   && p() && e('3');        // 获取空间1的成员数量
r($spaceTester->getMemberListByAccountTest(1, 'admin')) && p('account') && e('admin');   // 获取空间1中的admin成员
r($spaceTester->getMemberListBySpaceCountTest(2))   && p() && e('1');        // 获取空间2的成员数量
r($spaceTester->getMemberListByAccountTest(1, 'admin')) && p('role') && e('manager');    // 验证空间1中admin的角色