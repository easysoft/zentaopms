#!/usr/bin/env php
<?php

/**
title=测试 spaceModel::getByID();
timeout=0
cid=16025

- 查询ID=0的空间返回接口错误 @Path 参数解析失败。
- 查询第1个真实空间的名称匹配动态创建值 @1
- 查询第1个真实空间的唯一标识匹配动态创建值 @1
- 查询第2个真实空间的访问控制 @open
- 查询第1个真实空间中admin的角色 @manager
- 查询不存在的空间返回接口错误 @查询空间不存在
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
$spaceA->name        = "ut-getbyid-space-a-{$suffix}";
$spaceA->code        = "utgetbyidspacea{$suffix}";
$spaceA->desc        = 'getByID private space';
$spaceA->acl         = 'private';
$spaceA->auth        = 'extend';
$spaceA->createdBy   = 'admin';
$spaceA->createdDate = '2026-07-29 10:15:00';

$spaceB = new stdClass();
$spaceB->name        = "ut-getbyid-space-b-{$suffix}";
$spaceB->code        = "utgetbyidspaceb{$suffix}";
$spaceB->desc        = 'getByID open space';
$spaceB->acl         = 'open';
$spaceB->auth        = 'extend';
$spaceB->createdBy   = 'admin';
$spaceB->createdDate = '2026-07-29 10:15:00';

$spaceID1 = $spaceTester->createTest($spaceA);
$spaceID2 = $spaceTester->createTest($spaceB);

r($spaceTester->getByIDTest(0))                                   && p('apiMessage') && e('Path 参数解析失败。'); // 查询ID=0的空间返回接口错误
r($spaceTester->getByIDFieldEqualsTest((int)$spaceID1, 'name', "ut-getbyid-space-a-{$suffix}")) && p() && e('1'); // 查询第1个真实空间的名称匹配动态创建值
r($spaceTester->getByIDFieldEqualsTest((int)$spaceID1, 'code', "utgetbyidspacea{$suffix}"))     && p() && e('1'); // 查询第1个真实空间的唯一标识匹配动态创建值
r($spaceTester->getByIDFieldTest((int)$spaceID2, 'acl'))          && p()   && e('open');               // 查询第2个真实空间的访问控制
r($spaceTester->getByIDMemberFieldTest((int)$spaceID1, 'admin', 'role')) && p() && e('manager');        // 查询第1个真实空间中admin的角色
r($spaceTester->getByIDTest(999999))                              && p('apiMessage') && e('查询空间不存在');       // 查询不存在的空间返回接口错误
