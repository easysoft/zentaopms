#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::sendToUser();
timeout=0
cid=19706

- 步骤1：测试钉钉用户类型（无actionID） @0
- 步骤2：测试企业微信用户类型（无actionID） @0
- 步骤3：测试飞书用户类型（无actionID） @0
- 步骤4：测试无效webhook类型 @0
- 步骤5：测试dinggroup类型（应该跳过） @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$webhook = zenData('webhook');
$webhook->id->range('1-5');
$webhook->type->range('dinguser{1},wechatuser{1},feishuuser{1},dinggroup{1},default{1}');
$webhook->secret->range('{"appKey":"test_app_key","appSecret":"test_app_secret","agentId":"123456"}',
                       '{"appKey":"wechat_key","appSecret":"wechat_secret","agentId":"654321"}',
                       '{"appId":"feishu_app_id","appSecret":"feishu_app_secret"}',
                       'test_secret',
                       'default_secret');
$webhook->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$webhookTest = new webhookTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 创建测试用的webhook对象
$testWebhook1 = new stdclass();
$testWebhook1->id = 1;
$testWebhook1->type = 'dinguser';
$testWebhook1->secret = '{"appKey":"test_app_key","appSecret":"test_app_secret","agentId":"123456"}';

$testWebhook2 = new stdclass();
$testWebhook2->id = 2;
$testWebhook2->type = 'wechatuser';
$testWebhook2->secret = '{"appKey":"wechat_key","appSecret":"wechat_secret","agentId":"654321"}';

$testWebhook3 = new stdclass();
$testWebhook3->id = 3;
$testWebhook3->type = 'feishuuser';
$testWebhook3->secret = '{"appId":"feishu_app_id","appSecret":"feishu_app_secret"}';

$testWebhook4 = new stdclass();
$testWebhook4->id = 4;
$testWebhook4->type = 'unknown';
$testWebhook4->secret = 'invalid_secret';

$testWebhook5 = new stdclass();
$testWebhook5->id = 5;
$testWebhook5->type = 'dinggroup';
$testWebhook5->secret = 'test_secret';

$testSendData = '{"msgtype":"text","text":{"content":"测试消息"}}';

r($webhookTest->sendToUserTest($testWebhook1, $testSendData, 0, '')) && p() && e('0'); // 步骤1：测试钉钉用户类型（无actionID）
r($webhookTest->sendToUserTest($testWebhook2, $testSendData, 0, '')) && p() && e('0'); // 步骤2：测试企业微信用户类型（无actionID）
r($webhookTest->sendToUserTest($testWebhook3, $testSendData, 0, '')) && p() && e('0'); // 步骤3：测试飞书用户类型（无actionID）
r($webhookTest->sendToUserTest($testWebhook4, $testSendData, 0, '')) && p() && e('0'); // 步骤4：测试无效webhook类型
r($webhookTest->sendToUserTest($testWebhook5, $testSendData, 0, '')) && p() && e('0'); // 步骤5：测试dinggroup类型（应该跳过）