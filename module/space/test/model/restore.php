#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::restore();
timeout=0
cid=16031

- 还原不存在的空间返回真实接口错误 @恢复空间失败
- 还原空间ID=0返回路径解析错误 @Path 参数解析失败。
- 还原未删除的空间A返回真实接口错误 @恢复空间失败
- 还原未删除的空间B返回真实接口错误 @恢复空间失败
- 还原未删除的空间C返回真实接口错误 @恢复空间失败

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(0);
zenData('ops_spaceuser')->gen(0);
zenData('entry')->loadYaml('entry')->gen(2);

su('admin');

$spaceTester = new spaceModelTest();
$suffix      = date('YmdHis') . mt_rand(1000, 9999);

$spaceA = new stdClass();
$spaceA->name        = "ut-restore-space-a-{$suffix}";
$spaceA->code        = "utrestorespacea{$suffix}";
$spaceA->desc        = 'restore space A';
$spaceA->acl         = 'open';
$spaceA->auth        = 'extend';
$spaceA->createdBy   = 'admin';
$spaceA->createdDate = '2026-07-29 10:20:00';

$spaceB = clone $spaceA;
$spaceB->name = "ut-restore-space-b-{$suffix}";
$spaceB->code = "utrestorespaceb{$suffix}";

$spaceC = clone $spaceA;
$spaceC->name = "ut-restore-space-c-{$suffix}";
$spaceC->code = "utrestorespacec{$suffix}";

$spaceID1 = (int)$spaceTester->createTest($spaceA);
$spaceID2 = (int)$spaceTester->createTest($spaceB);
$spaceID3 = (int)$spaceTester->createTest($spaceC);

r($spaceTester->restoreErrorTest(999999, 0)) && p() && e('恢复空间失败');   // 还原不存在的空间返回真实接口错误
r($spaceTester->restoreErrorTest(0, 0))      && p() && e('Path 参数解析失败。'); // 还原空间ID=0返回路径解析错误
r($spaceTester->restoreErrorTest($spaceID1, 0)) && p() && e('恢复空间失败'); // 还原未删除的空间A返回真实接口错误
r($spaceTester->restoreErrorTest($spaceID2, 0)) && p() && e('恢复空间失败'); // 还原未删除的空间B返回真实接口错误
r($spaceTester->restoreErrorTest($spaceID3, 0)) && p() && e('恢复空间失败'); // 还原未删除的空间C返回真实接口错误
