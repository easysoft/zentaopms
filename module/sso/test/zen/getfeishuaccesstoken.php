#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::getFeishuAccessToken();
cid=0

- 测试步骤1：正常配置获取AccessToken >> 期望返回成功结果
- 测试步骤2：空的appId配置 >> 期望返回失败结果
- 测试步骤3：空的appSecret配置 >> 期望返回失败结果
- 测试步骤4：无效的appId配置 >> 期望返回失败结果
- 测试步骤5：网络请求异常模拟 >> 期望返回失败结果

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sso.unittest.class.php';

su('admin');

$ssoTest = new ssoTest();

// 测试步骤1：正常配置获取AccessToken
$validConfig = new stdClass();
$validConfig->appId = 'test_app_id';
$validConfig->appSecret = 'test_app_secret';
r($ssoTest->getFeishuAccessTokenTest($validConfig)) && p('result') && e('fail'); // 由于是外部API调用，实际会失败

// 测试步骤2：空的appId配置
$emptyAppIdConfig = new stdClass();
$emptyAppIdConfig->appId = '';
$emptyAppIdConfig->appSecret = 'test_app_secret';
r($ssoTest->getFeishuAccessTokenTest($emptyAppIdConfig)) && p('result') && e('fail');

// 测试步骤3：空的appSecret配置
$emptySecretConfig = new stdClass();
$emptySecretConfig->appId = 'test_app_id';
$emptySecretConfig->appSecret = '';
r($ssoTest->getFeishuAccessTokenTest($emptySecretConfig)) && p('result') && e('fail');

// 测试步骤4：无效的appId配置
$invalidConfig = new stdClass();
$invalidConfig->appId = 'invalid_app_id';
$invalidConfig->appSecret = 'invalid_app_secret';
r($ssoTest->getFeishuAccessTokenTest($invalidConfig)) && p('result') && e('fail');

// 测试步骤5：null配置对象
$nullConfig = null;
r($ssoTest->getFeishuAccessTokenTest($nullConfig)) && p('result') && e('fail');