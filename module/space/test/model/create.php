#!/usr/bin/env php
<?php

/**
title=测试 spaceModel::create();
timeout=0
cid=16022

- 正常创建空间 @1
- 重复空间标识返回真实接口错误 @创建空间异常，空间标识已存在
- 传入manager字段后写入本地管理员数量 @2
- code为空时返回接口错误 @Body 参数解析失败。
- name为空时返回接口错误 @Body 参数解析失败。
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

$space = new stdClass();
$space->name        = "ut-space-{$suffix}";
$space->code        = "utspace{$suffix}";
$space->desc        = 'unit test create space';
$space->acl         = 'open';
$space->auth        = 'extend';
$space->createdBy   = 'admin';
$space->createdDate = '2026-07-29 10:00:00';

$duplicateCode = clone $space;
$duplicateCode->name = "ut-space-dup-{$suffix}";

$withManager = clone $space;
$withManager->name    = "ut-space-manager-{$suffix}";
$withManager->code    = "utspacemanager{$suffix}";
$withManager->manager = 'admin,test1';

$emptyCode = clone $space;
$emptyCode->name = "ut-space-empty-code-{$suffix}";
$emptyCode->code = '';

$emptyName = clone $space;
$emptyName->name = '';
$emptyName->code = "utspace-empty-name-{$suffix}";

r($spaceTester->createSuccessTest($space))       && p() && e('1');                         // 正常创建空间
r($spaceTester->createErrorTest($duplicateCode)) && p() && e('创建空间异常，空间标识已存在'); // 重复空间标识返回真实接口错误
r($spaceTester->createAndGetManagerCountTest($withManager)) && p() && e('2');              // 传入manager字段后写入本地管理员数量
r($spaceTester->createErrorTest($emptyCode))     && p() && e('Body 参数解析失败。');         // code为空时返回接口错误
r($spaceTester->createErrorTest($emptyName))     && p() && e('Body 参数解析失败。');         // name为空时返回接口错误
