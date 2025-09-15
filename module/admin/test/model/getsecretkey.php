#!/usr/bin/env php
<?php

/**

title=测试 adminModel::getSecretKey();
timeout=0
cid=0

- 步骤1：配置缺失的情况 @md5_error
- 步骤2：设置社区版配置 @md5_error
- 步骤3：设置私钥配置 @api_fail
- 步骤4：设置API根路径 @type_error
- 步骤5：完整配置测试 @type_error

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

// 2. 用户登录（管理员角色）
su('admin');

// 3. 创建测试实例
$adminTest = new adminTest();

// 4. 执行测试步骤（必须包含至少5个测试步骤）
r($adminTest->getSecretKeyTest()) && p() && e('md5_error'); // 步骤1：配置缺失的情况

global $config;
$config->global->community   = 'test';
r($adminTest->getSecretKeyTest()) && p() && e('md5_error'); // 步骤2：设置社区版配置

$config->global->ztPrivateKey = 'testkey123';
r($adminTest->getSecretKeyTest()) && p() && e('api_fail'); // 步骤3：设置私钥配置

$config->admin->apiRoot = 'https://api.zentao.net/api';
r($adminTest->getSecretKeyTest()) && p() && e('type_error'); // 步骤4：设置API根路径

r($adminTest->getSecretKeyTest()) && p() && e('type_error'); // 步骤5：完整配置测试