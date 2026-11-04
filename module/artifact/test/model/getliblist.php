#!/usr/bin/env php
<?php

/**

title=测试 artifactModel::getLibList()
timeout=0
cid=0

- 管理员可以查看全部制品库 @5
- 管理员查看空间级制品库 @2
- 管理员查看代码库级制品库 @3
- 用户1查看空间级制品库时只保留有权限的空间 @1
- 用户1查看代码库级制品库时只保留有权限的代码库 @1
- 用户1查看全部制品库时同时保留有权限的空间和代码库制品库 @2
- 用户1查看有权限空间下的空间级制品库第1条name属性 @制品库1
- 用户1查看有权限代码库下的代码库级制品库第2条name属性 @制品库2
- 用户1查看无权限空间时返回空列表 @0
- 无空间和代码库权限的用户看不到任何制品库 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app;
$app->rawModule = 'artifact';
$app->rawMethod = 'browse';
$app->tab       = 'artifact';

zenData('user')->gen(3);
zenData('group')->gen(0);
zenData('usergroup')->gen(0);

zenData('ops_space')->gen(0);
$space = zenData('ops_space');
$space->id->range('1,2');
$space->name->range('空间1,空间2');
$space->code->range('space1,space2');
$space->acl->range('private{2}');
$space->auth->range('extend{2}');
$space->deleted->range('0{2}');
$space->gen(2);

/* user1是私有空间1的成员，空间2私有且无成员。 */
$spaceUser = zenData('ops_spaceuser');
$spaceUser->space->range('1');
$spaceUser->role->range('manager');
$spaceUser->account->range('user1');
$spaceUser->gen(1);

zenData('ops_repo')->gen(0);
$repo = zenData('ops_repo');
$repo->id->range('1-3');
$repo->spaceID->range('1,1,2');
$repo->name->range('代码库1,代码库2,代码库3');
$repo->gitUID->range('gituid000000000000000000000000000000000001,gituid000000000000000000000000000000000002,gituid000000000000000000000000000000000003');
$repo->acl->range('open,private,open');
$repo->status->range('active{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

/* 代码库1开放，代码库2私有且无成员，代码库3属于无权限的空间2。 */
zenData('ops_repouser')->gen(0);

$lib = zenData('ops_artifact_libs');
$lib->id->range('1-5');
$lib->spaceID->range('1,1,1,2,2');
$lib->repoID->range('0,1,2,0,3');
$lib->name->range('制品库1,制品库2,制品库3,制品库4,制品库5');
$lib->code->range('lib1,lib2,lib3,lib4,lib5');
$lib->type->range('file{5}');
$lib->scope->range('space,repo,repo,space,repo');
$lib->createdBy->range('admin{5}');
$lib->deleted->range('0{5}');
$lib->gen(5);

su('admin');

$tester = new artifactModelTest();

r(count($tester->getLibListTest(0, 0, 'all')))   && p() && e('5'); // 管理员可以查看全部制品库
r(count($tester->getLibListTest(0, 0, 'space'))) && p() && e('2'); // 管理员查看空间级制品库
r(count($tester->getLibListTest(0, 0, 'repo')))  && p() && e('3'); // 管理员查看代码库级制品库

su('user1');

r(count($tester->getLibListTest(0, 0, 'space'))) && p() && e('1'); // 用户1查看空间级制品库时只保留有权限的空间
r(count($tester->getLibListTest(0, 0, 'repo')))  && p() && e('1'); // 用户1查看代码库级制品库时只保留有权限的代码库
r(count($tester->getLibListTest(0, 0, 'all')))   && p() && e('2'); // 用户1查看全部制品库时同时保留有权限的空间和代码库制品库
r($tester->getLibListTest(0, 0, 'space'))        && p('1:name') && e('制品库1'); // 用户1查看有权限空间下的空间级制品库第1条的name属性
r($tester->getLibListTest(0, 0, 'repo'))         && p('2:name') && e('制品库2'); // 用户1查看有权限代码库下的代码库级制品库第2条的name属性
r(count($tester->getLibListTest(2, 0, 'space'))) && p() && e('0'); // 用户1查看无权限空间时返回空列表

su('user2');

r(count($tester->getLibListTest(0, 0, 'all'))) && p() && e('0'); // 无空间和代码库权限的用户看不到任何制品库
