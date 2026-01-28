#!/usr/bin/env php
<?php

/**

title=测试 webhookTao::getFeishuSecret();
timeout=0
cid=19711

- 执行webhookTest模块的getFeishuSecretTest方法，参数是$validWebhook 属性url @https://open.feishu.cn/open-apis/
- 执行webhookTest模块的getFeishuSecretTest方法，参数是$emptyAppIdWebhook 属性feishuAppId @『飞书App ID』不能为空。
- 执行webhookTest模块的getFeishuSecretTest方法，参数是$emptyAppSecretWebhook 属性feishuAppSecret @『飞书App Secret』不能为空。
- 执行webhookTest模块的getFeishuSecretTest方法，参数是$emptyBothWebhook 属性feishuAppId @『飞书App ID』不能为空。
- 执行webhookTest模块的getFeishuSecretTest方法，参数是$urlTestWebhook 属性url @https://open.feishu.cn/open-apis/

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$table = zenData('webhook');
$table->id->range('1-10');
$table->type->range('feishu{10}');
$table->name->range('飞书测试{10}');
$table->gen(5);

su('admin');

$webhookTest = new webhookTaoTest();

// 步骤1：正常情况测试 - 有效的feishuAppId和feishuAppSecret
$validWebhook = new stdClass();
$validWebhook->feishuAppId = 'test_app_id_123';
$validWebhook->feishuAppSecret = 'test_app_secret_456';
r($webhookTest->getFeishuSecretTest($validWebhook)) && p('url') && e('https://open.feishu.cn/open-apis/');

// 步骤2：feishuAppId为空
$emptyAppIdWebhook = new stdClass();
$emptyAppIdWebhook->feishuAppId = '';
$emptyAppIdWebhook->feishuAppSecret = 'test_app_secret_456';
r($webhookTest->getFeishuSecretTest($emptyAppIdWebhook)) && p('feishuAppId') && e('『飞书App ID』不能为空。');

// 步骤3：feishuAppSecret为空
$emptyAppSecretWebhook = new stdClass();
$emptyAppSecretWebhook->feishuAppId = 'test_app_id_123';
$emptyAppSecretWebhook->feishuAppSecret = '';
r($webhookTest->getFeishuSecretTest($emptyAppSecretWebhook)) && p('feishuAppSecret') && e('『飞书App Secret』不能为空。');

// 步骤4：两个参数都为空
$emptyBothWebhook = new stdClass();
$emptyBothWebhook->feishuAppId = '';
$emptyBothWebhook->feishuAppSecret = '';
r($webhookTest->getFeishuSecretTest($emptyBothWebhook)) && p('feishuAppId') && e('『飞书App ID』不能为空。');

// 步骤5：验证返回对象的url属性
$urlTestWebhook = new stdClass();
$urlTestWebhook->feishuAppId = 'test_app_id_789';
$urlTestWebhook->feishuAppSecret = 'test_app_secret_012';
r($webhookTest->getFeishuSecretTest($urlTestWebhook)) && p('url') && e('https://open.feishu.cn/open-apis/');