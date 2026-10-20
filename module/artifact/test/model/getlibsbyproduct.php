#!/usr/bin/env php
<?php

/**

title=测试 artifactModel::getLibsByProduct()
timeout=0
cid=0

- 管理员按产品查询代码库级制品库 @1
- 管理员查询关联多个代码库的产品 @2
- 产品ID为0时返回空列表 @0
- 产品ID不存在时返回空列表 @0
- 空间级制品库不返回 @1
- 已被删除的制品库不返回 @0
- 普通用户只能看到有权限代码库的制品库 @1
- 普通用户查询无权限代码库的产品返回空列表 @0
- 查询结果保留制品库名称 @制品库1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
zenData('group')->gen(0);
zenData('usergroup')->gen(0);

$space = zenData('ops_space');
$space->id->range('1,2');
$space->name->range('空间1,空间2');
$space->code->range('space1,space2');
$space->acl->range('private{2}');
$space->auth->range('extend{2}');
$space->deleted->range('0{2}');
$space->gen(2);

/* user1是空间1的成员，空间2私有且无成员。 */
zenData('ops_repouser')->gen(0);
$spaceUser = zenData('ops_spaceuser');
$spaceUser->space->range('1');
$spaceUser->account->range('user1');
$spaceUser->role->range('manager');
$spaceUser->gen(1);

$repo = zenData('ops_repo');
$repo->id->range('1-3');
$repo->spaceID->range('1,2,1');
$repo->name->range('代码库1,代码库2,代码库3');
$repo->product->range('`1,2`,`2,3`,4');
$repo->acl->range('open{3}');
$repo->status->range('active{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

/* 独立准备制品库数据：共5条，其中制品库2为空间级、制品库4已删除、制品库5不属于任何代码库。 */
$lib = zenData('ops_artifact_libs');
$lib->id->range('1-5');
$lib->spaceID->range('1,1,2,1,1');
$lib->repoID->range('1,1,2,3,0');
$lib->name->range('制品库1,制品库2,制品库3,制品库4,制品库5');
$lib->code->range('lib1,lib2,lib3,lib4,lib5');
$lib->type->range('file{5}');
$lib->scope->range('repo,space,repo,repo,repo');
$lib->createdBy->range('admin{5}');
$lib->deleted->range('0,0,0,1,0');
$lib->gen(5);

$tester = new artifactModelTest();

su('admin');

r(count($tester->getLibsByProductTest(1))) && p() && e('1'); // 管理员按产品查询代码库级制品库
r(count($tester->getLibsByProductTest(2))) && p() && e('2'); // 管理员查询关联多个代码库的产品
r(count($tester->getLibsByProductTest(0))) && p() && e('0'); // 产品ID为0时返回空列表
r(count($tester->getLibsByProductTest(999999))) && p() && e('0'); // 产品ID不存在时返回空列表
r(count($tester->getLibsByProductTest(3))) && p() && e('1'); // 空间级制品库不返回
r(count($tester->getLibsByProductTest(4))) && p() && e('0'); // 已被删除的制品库不返回

su('user1');

r(count($tester->getLibsByProductTest(2))) && p() && e('1'); // 普通用户只能看到有权限代码库的制品库
r(count($tester->getLibsByProductTest(3))) && p() && e('0'); // 普通用户查询无权限代码库的产品返回空列表

su('admin');

r($tester->getLibsByProductTest(1)) && p('1:name') && e('制品库1'); // 查询结果保留制品库名称
