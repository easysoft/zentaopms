#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::getBindAccount();
timeout=0
cid=19690

- 步骤1：正常查询存在的绑定账号 @admin
- 步骤2：查询不存在的webhookID @0
- 步骤3：查询不存在的webhookType @0
- 步骤4：查询不存在的openID @0
- 步骤5：查询空参数情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

// 2. zendata数据准备
$table = zenData('oauth');
$table->account->range('admin,user1,user2,user3,user4');
$table->openID->range('openid1,openid2,openid3,openid4,openid5');
$table->providerType->range('webhook{3},gitlab{2}');
$table->providerID->range('1{3},2{2}');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$webhookTest = new webhookTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($webhookTest->getBindAccountTest(1, 'webhook', 'openid1')) && p() && e('admin');    // 步骤1：正常查询存在的绑定账号
r($webhookTest->getBindAccountTest(999, 'webhook', 'openid1')) && p() && e('0');   // 步骤2：查询不存在的webhookID
r($webhookTest->getBindAccountTest(1, 'nonexist', 'openid1')) && p() && e('0');    // 步骤3：查询不存在的webhookType
r($webhookTest->getBindAccountTest(1, 'webhook', 'nonexist')) && p() && e('0');    // 步骤4：查询不存在的openID
r($webhookTest->getBindAccountTest(0, '', '')) && p() && e('0');                   // 步骤5：查询空参数情况