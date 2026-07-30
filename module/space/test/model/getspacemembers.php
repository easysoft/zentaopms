#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getSpaceMembers();
timeout=0
cid=0

- 查询有效空间ID=1的成员并验证结果类型 @1
- 查询有效空间ID=2的成员并验证结果类型 @1
- 查询空间ID=0的成员并验证结果类型 @1
- 查询空间ID=9999的成员为空并验证结果类型 @1
- 查询空间ID=1并用allVision参数查询并验证结果类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

global $tester;
$vision      = $tester->config->vision;
$otherVision = $vision == 'rnd' ? 'or' : 'rnd';

$spaceUser = zenData('ops_spaceuser');
$spaceUser->id->range('1-2');
$spaceUser->space->range('1,2');
$spaceUser->account->range('admin,test3');
$spaceUser->role->range('manager,manager');
$spaceUser->gen(2);

$group = zenData('group');
$group->id->range('1-3');
$group->name->range('space1-group,space2-group,space1-hidden-group');
$group->project->range('0{3}');
$group->devopsSpace->range('1,2,1');
$group->vision->range("{$vision},{$vision},{$otherVision}");
$group->gen(3);

$userGroup = zenData('usergroup');
$userGroup->account->range('admin,test1,test3,test4');
$userGroup->group->range('1,1,2,3');
$userGroup->project->range('0{4}');
$userGroup->gen(4);

$repo = zenData('ops_repo');
$repo->id->range('1-2');
$repo->spaceID->range('1,2');
$repo->product->range('1,2');
$repo->name->range('repo-one,repo-two');
$repo->status->range('active{2}');
$repo->deleted->range('0{2}');
$repo->gen(2);

$repoUser = zenData('ops_repouser');
$repoUser->id->range('1-3');
$repoUser->repo->range('1,1,2');
$repoUser->account->range('admin,test2,test3');
$repoUser->gen(3);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getSpaceMembersCountTest(1))             && p() && e('3');        // 查询空间1下的成员数量
r($spaceTester->getSpaceMembersCountTest(2))             && p() && e('1');        // 查询空间2下的成员数量
r($spaceTester->getSpaceMembersCountTest(0))             && p() && e('4');        // 查询所有空间汇总成员数量
r($spaceTester->getSpaceMembersCountTest(9999))          && p() && e('0');        // 查询不存在空间的成员为空
r($spaceTester->getSpaceMembersCountTest(1, true))       && p() && e('4');        // 查询空间1在全视图下的成员数量
r($spaceTester->getSpaceMembersFieldTest(1, 'admin', 'role')) && p() && e('manager'); // 查询空间1中管理员角色
