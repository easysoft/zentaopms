#!/usr/bin/env php
<?php

/**

title=测试 ssoModel::checkKey();
timeout=0
cid=18402

- 步骤1：SSO功能未开启 @0
- 步骤2：密钥为空 @0
- 步骤3：密钥不匹配 @0
- 步骤4：hash参数为空 @0
- 步骤5：密钥完全匹配 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$ssoTest = new ssoModelTest();

// 4. 清理环境
unset($_GET['hash']);
global $tester;
if(isset($tester->config->sso)) {
    unset($tester->config->sso->turnon);
    unset($tester->config->sso->key);
}

// 5. 强制要求：必须包含至少5个测试步骤
r($ssoTest->checkKeyTest()) && p() && e('0'); // 步骤1：SSO功能未开启

$tester->config->sso->turnon = true;
r($ssoTest->checkKeyTest()) && p() && e('0'); // 步骤2：密钥为空

$tester->config->sso->key = 'test_secret_key';
$_GET['hash'] = 'wrong_hash';
r($ssoTest->checkKeyTest()) && p() && e('0'); // 步骤3：密钥不匹配

$_GET['hash'] = '';
r($ssoTest->checkKeyTest()) && p() && e('0'); // 步骤4：hash参数为空

$_GET['hash'] = 'test_secret_key';
r($ssoTest->checkKeyTest()) && p() && e('1'); // 步骤5：密钥完全匹配