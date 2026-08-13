#!/usr/bin/env php
<?php
/**
title=测试 spaceModel::deleteSpace();
timeout=0
cid=16023

- 删除空间ID=0返回路径解析错误 @Path 参数解析失败。
- 删除不存在的空间返回真实接口错误 @[zh-CN:space.delete_error]
- 删除真实空间返回成功 @1
- 删除真实空间后生成删除动作 @1
- 删除另一个真实空间返回成功 @1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(0);
zenData('ops_spaceuser')->gen(0);
zenData('entry')->loadYaml('entry')->gen(1);

su('admin');

$spaceTester = new spaceModelTest();
$suffix      = date('YmdHis') . mt_rand(1000, 9999);

$spaceA = new stdClass();
$spaceA->name        = "ut-delete-space-a-{$suffix}";
$spaceA->code        = "utdeletespacea{$suffix}";
$spaceA->desc        = 'delete space A';
$spaceA->acl         = 'open';
$spaceA->auth        = 'extend';
$spaceA->createdBy   = 'admin';
$spaceA->createdDate = '2026-07-29 10:19:00';

$spaceB = clone $spaceA;
$spaceB->name = "ut-delete-space-b-{$suffix}";
$spaceB->code = "utdeletespaceb{$suffix}";

$spaceC = clone $spaceA;
$spaceC->name = "ut-delete-space-c-{$suffix}";
$spaceC->code = "utdeletespacec{$suffix}";

$spaceD = clone $spaceA;
$spaceD->name = "ut-delete-space-d-{$suffix}";
$spaceD->code = "utdeletespaced{$suffix}";

$spaceE = clone $spaceA;
$spaceE->name = "ut-delete-space-e-{$suffix}";
$spaceE->code = "utdeletespacee{$suffix}";

$spaceID1 = (int)$spaceTester->createTest($spaceA);
$spaceID2 = (int)$spaceTester->createTest($spaceB);
$spaceID3 = (int)$spaceTester->createTest($spaceC);
$spaceID4 = (int)$spaceTester->createTest($spaceD);
$spaceID5 = (int)$spaceTester->createTest($spaceE);

r($spaceTester->deleteSpaceErrorTest(0))              && p() && e('Path 参数解析失败。');        // 删除空间ID=0返回路径解析错误
r($spaceTester->deleteSpaceErrorTest(999999))         && p() && e('[zh-CN:space.delete_error]'); // 删除不存在的空间返回真实接口错误
r($spaceTester->deleteSpaceSuccessTest($spaceID3))    && p() && e('1');                          // 删除真实空间返回成功
r($spaceTester->deleteSpaceHasActionTest($spaceID4))  && p() && e('1');                          // 删除真实空间后生成删除动作
r($spaceTester->deleteSpaceSuccessTest($spaceID5))    && p() && e('1');                          // 删除另一个真实空间返回成功
