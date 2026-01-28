#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::getOpenIdList();
timeout=0
cid=19700

- 步骤1：传入toList参数 @openid_001,openid_002

- 步骤2：空的toList和actionID @0
- 步骤3：空actionID测试 @0
- 步骤4：不同webhook用户 @openid_003,openid_004

- 步骤5：数组格式toList @openid_001,openid_002

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 数据准备 - 简化版本
$oauthTable = zenData('oauth');
$oauthTable->account->range('admin,user1,user2,user3');
$oauthTable->openID->range('openid_001,openid_002,openid_003,openid_004');
$oauthTable->providerType->range('webhook');
$oauthTable->providerID->range('1{2},2{2}');
$oauthTable->gen(4);

su('admin');

$webhookTest = new webhookModelTest();

// 执行5个测试步骤
r($webhookTest->getOpenIdListTest(1, 0, 'admin,user1')) && p() && e('openid_001,openid_002'); // 步骤1：传入toList参数
r($webhookTest->getOpenIdListTest(1, 0, '')) && p() && e('0'); // 步骤2：空的toList和actionID
r($webhookTest->getOpenIdListTest(1, 0)) && p() && e('0'); // 步骤3：空actionID测试
r($webhookTest->getOpenIdListTest(2, 0, 'user2,user3')) && p() && e('openid_003,openid_004'); // 步骤4：不同webhook用户
r($webhookTest->getOpenIdListTest(1, 0, array('admin', 'user1'))) && p() && e('openid_001,openid_002'); // 步骤5：数组格式toList