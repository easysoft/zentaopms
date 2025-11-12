#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::computeAuth();
timeout=0
cid=0

- 步骤1:测试正常token生成认证字符串,MD5值长度为32 @32
- 步骤2:测试空token生成认证字符串,MD5值长度为32 @32
- 步骤3:测试包含特殊字符的token生成认证字符串,MD5值长度为32 @32
- 步骤4:测试相同token生成相同的认证字符串 @1
- 步骤5:测试不同token生成不同的认证字符串 @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 设置SSO配置
global $config;
if(!isset($config->sso))
{
    $config->sso = new stdClass();
}
$config->sso->code = 'test_sso_code';
$config->sso->key  = 'test_sso_key';

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$ssoTest = new ssoZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r(strlen($ssoTest->computeAuthTest('test_token_123'))) && p() && e('32'); // 步骤1:测试正常token生成认证字符串,MD5值长度为32
r(strlen($ssoTest->computeAuthTest(''))) && p() && e('32'); // 步骤2:测试空token生成认证字符串,MD5值长度为32
r(strlen($ssoTest->computeAuthTest('!@#$%^&*()'))) && p() && e('32'); // 步骤3:测试包含特殊字符的token生成认证字符串,MD5值长度为32
r($ssoTest->computeAuthTest('same_token') == $ssoTest->computeAuthTest('same_token')) && p() && e('1'); // 步骤4:测试相同token生成相同的认证字符串
r($ssoTest->computeAuthTest('token_a') != $ssoTest->computeAuthTest('token_b')) && p() && e('1'); // 步骤5:测试不同token生成不同的认证字符串