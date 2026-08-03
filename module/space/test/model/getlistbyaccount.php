#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getListByAccount();
timeout=0
cid=16027

- 管理员模式下查询test1返回全部真实空间 @3
- 非管理员模式下查询test1返回当前公开空间数量 @3
- 非管理员模式下查询test2的空间名称匹配动态创建值 @1
- 非管理员模式下查询test1时返回公开空间A @1
- 非管理员模式下查询不存在用户返回公开空间数量 @3
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(0);
zenData('ops_spaceuser')->gen(0);
zenData('group')->gen(0);
zenData('usergroup')->gen(0);
zenData('ops_repo')->gen(0);
zenData('ops_repouser')->gen(0);
zenData('entry')->loadYaml('entry')->gen(1);

su('admin');

$spaceTester = new spaceModelTest();
$suffix      = date('YmdHis') . mt_rand(1000, 9999);

$spaceA = new stdClass();
$spaceA->name        = "ut-list-account-space-a-{$suffix}";
$spaceA->code        = "utlistaccounta{$suffix}";
$spaceA->desc        = 'getListByAccount space A';
$spaceA->acl         = 'open';
$spaceA->auth        = 'extend';
$spaceA->createdBy   = 'admin';
$spaceA->createdDate = '2026-07-29 10:17:00';

$spaceB = new stdClass();
$spaceB->name        = "ut-list-account-space-b-{$suffix}";
$spaceB->code        = "utlistaccountb{$suffix}";
$spaceB->desc        = 'getListByAccount space B';
$spaceB->acl         = 'open';
$spaceB->auth        = 'extend';
$spaceB->createdBy   = 'admin';
$spaceB->createdDate = '2026-07-29 10:17:00';

$spaceC = new stdClass();
$spaceC->name        = "ut-list-account-space-c-{$suffix}";
$spaceC->code        = "utlistaccountc{$suffix}";
$spaceC->desc        = 'getListByAccount space C';
$spaceC->acl         = 'open';
$spaceC->auth        = 'extend';
$spaceC->createdBy   = 'admin';
$spaceC->createdDate = '2026-07-29 10:17:00';

$spaceID1 = (int)$spaceTester->createTest($spaceA);
$spaceID2 = (int)$spaceTester->createTest($spaceB);
$spaceID3 = (int)$spaceTester->createTest($spaceC);

$spaceUser = zenData('ops_spaceuser');
$spaceUser->id->range('101-104');
$spaceUser->space->range("{$spaceID1},{$spaceID2},{$spaceID3},{$spaceID2}");
$spaceUser->account->range('test1,test1,test2,test3');
$spaceUser->role->range('member{4}');
$spaceUser->gen(4);

r($spaceTester->getListByAccountCountTest('test1'))                                       && p() && e('3');                                   // 管理员模式下查询test1返回全部真实空间
$tester->app->user->admin = false;
$spaceTester->instance->app->user->admin = false;
r($spaceTester->getListByAccountCountTest('test1'))                                       && p() && e('3');                                   // 非管理员模式下查询test1返回当前公开空间数量
r($spaceTester->getListByAccountSpaceFieldEqualsTest('test2', $spaceID3, 'name', "ut-list-account-space-c-{$suffix}")) && p() && e('1'); // 非管理员模式下查询test2的空间名称匹配动态创建值
r($spaceTester->getListByAccountSpaceFieldEqualsTest('test1', $spaceID1, 'acl', 'open')) && p() && e('1');                          // 非管理员模式下查询test1时返回公开空间A
r($spaceTester->getListByAccountCountTest('notexist'))                                    && p() && e('3');                                   // 非管理员模式下查询不存在用户返回公开空间数量
