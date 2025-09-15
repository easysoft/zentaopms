#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::fetchHook();
timeout=0
cid=0

- 步骤1：普通webhook返回SSL错误 @OpenSSL SSL_connect: SSL_ERROR_SYSCALL in connection to test.example.com:443
- 步骤2：钉钉用户webhook @0
- 步骤3：微信用户webhook @0
- 步骤4：飞书用户webhook @0
- 步骤5：钉钉群组webhook带签名 @{"errcode":300005,"errmsg":"token is not exist"}

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('webhook');
$table->id->range('1-10');
$table->type->range('default,dinguser,wechatuser,feishuuser,dinggroup');
$table->name->range('测试webhook{5}');
$table->url->range('https://oapi.dingtalk.com/robot/send?access_token=test{5}');
$table->contentType->range('application/json{5}');
$table->secret->range('testsecret{5}');
$table->deleted->range('0{10}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$webhookTest = new webhookTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试用的webhook对象
$normalWebhook = new stdclass();
$normalWebhook->type = 'default';
$normalWebhook->url = 'https://test.example.com/webhook';
$normalWebhook->contentType = 'application/json';

$dingUserWebhook = new stdclass();
$dingUserWebhook->type = 'dinguser';
$dingUserWebhook->id = 1;
$dingUserWebhook->secret = '{"appKey":"testkey","appSecret":"testsecret","agentId":"123"}';

$wechatUserWebhook = new stdclass();
$wechatUserWebhook->type = 'wechatuser';
$wechatUserWebhook->id = 2;
$wechatUserWebhook->secret = '{"appKey":"testkey","appSecret":"testsecret","agentId":"123"}';

$feishuUserWebhook = new stdclass();
$feishuUserWebhook->type = 'feishuuser';
$feishuUserWebhook->id = 3;
$feishuUserWebhook->secret = '{"appId":"testid","appSecret":"testsecret"}';

$dingGroupWebhook = new stdclass();
$dingGroupWebhook->type = 'dinggroup';
$dingGroupWebhook->url = 'https://oapi.dingtalk.com/robot/send?access_token=testtoken';
$dingGroupWebhook->secret = 'testsecret123';
$dingGroupWebhook->contentType = 'application/json';

$testData = '{"text":"测试消息内容"}';

r($webhookTest->fetchHookTest($normalWebhook, $testData, 1)) && p() && e('OpenSSL SSL_connect: SSL_ERROR_SYSCALL in connection to test.example.com:443'); // 步骤1：普通webhook返回SSL错误
r($webhookTest->fetchHookTest($dingUserWebhook, $testData, 1)) && p() && e('0'); // 步骤2：钉钉用户webhook
r($webhookTest->fetchHookTest($wechatUserWebhook, $testData, 1)) && p() && e('0'); // 步骤3：微信用户webhook  
r($webhookTest->fetchHookTest($feishuUserWebhook, $testData, 1)) && p() && e('0'); // 步骤4：飞书用户webhook
r($webhookTest->fetchHookTest($dingGroupWebhook, $testData, 1)) && p() && e('{"errcode":300005,"errmsg":"token is not exist"}'); // 步骤5：钉钉群组webhook带签名