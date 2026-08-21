#!/usr/bin/env php
<?php
/**

title=测试 spaceModel::getPairs();
timeout=0
cid=16028

- 管理员模式下返回第1个真实空间名称匹配动态创建值 @1
- 非管理员模式下返回test1的reset空间名称匹配动态创建值 @1
- 非管理员模式下查询不存在用户返回当前公开空间数量 @2
- 非管理员模式下查询test2返回当前公开空间数量 @2
- filterRepoCreate=true时当前仍返回公开空间数量 @2
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
$spaceA->name        = "ut-pairs-space-a-{$suffix}";
$spaceA->code        = "utpairsspacea{$suffix}";
$spaceA->desc        = 'getPairs space A';
$spaceA->acl         = 'private';
$spaceA->auth        = 'extend';
$spaceA->createdBy   = 'admin';
$spaceA->createdDate = '2026-07-29 10:18:00';

$spaceB = new stdClass();
$spaceB->name        = "ut-pairs-space-b-{$suffix}";
$spaceB->code        = "utpairsspaceb{$suffix}";
$spaceB->desc        = 'getPairs space B';
$spaceB->acl         = 'open';
$spaceB->auth        = 'reset';
$spaceB->createdBy   = 'admin';
$spaceB->createdDate = '2026-07-29 10:18:00';

$spaceC = new stdClass();
$spaceC->name        = "ut-pairs-space-c-{$suffix}";
$spaceC->code        = "utpairsspacec{$suffix}";
$spaceC->desc        = 'getPairs space C';
$spaceC->acl         = 'open';
$spaceC->auth        = 'extend';
$spaceC->createdBy   = 'admin';
$spaceC->createdDate = '2026-07-29 10:18:00';

$spaceID1 = (int)$spaceTester->createTest($spaceA);
$spaceID2 = (int)$spaceTester->createTest($spaceB);
$spaceID3 = (int)$spaceTester->createTest($spaceC);

$spaceUser = zenData('ops_spaceuser');
$spaceUser->id->range('111-113');
$spaceUser->space->range("{$spaceID1},{$spaceID2},{$spaceID3}");
$spaceUser->account->range('test1,test1,test2');
$spaceUser->role->range('member{3}');
$spaceUser->gen(3);

r($spaceTester->getPairsFieldEqualsTest('', $spaceID1, "ut-pairs-space-a-{$suffix}")) && p() && e('1'); // 管理员模式下返回第1个真实空间名称匹配动态创建值
$tester->app->user->admin = false;
$spaceTester->instance->app->user->admin = false;
r($spaceTester->getPairsFieldEqualsTest('test1', $spaceID2, "ut-pairs-space-b-{$suffix}")) && p() && e('1'); // 非管理员模式下返回test1的reset空间名称匹配动态创建值
r($spaceTester->getPairsCountTest('notexist'))           && p() && e('2');                            // 非管理员模式下查询不存在用户返回当前公开空间数量
r($spaceTester->getPairsCountTest('test2'))              && p() && e('2');                            // 非管理员模式下查询test2返回当前公开空间数量
r($spaceTester->getPairsCountTest('test1', true))        && p() && e('2');                            // filterRepoCreate=true时当前仍返回公开空间数量
