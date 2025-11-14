#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::bind();
timeout=0
cid=19685

- 执行webhookTest模块的bindTest方法，参数是1, $validUserList  @1
- 执行webhookTest模块的bindTest方法，参数是1, $emptyUserList  @0
- 执行webhookTest模块的bindTest方法，参数是1, $mixedUserList  @1
- 执行webhookTest模块的bindTest方法，参数是1, $duplicateUserList  @1
- 执行webhookTest模块的bindTest方法，参数是999, $validUserList2  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

// 准备测试数据
zenData('webhook')->gen(3);
zenData('oauth')->gen(0);

su('admin');

$webhookTest = new webhookTest();

// 步骤1：测试正常绑定用户到webhook
$validUserList = array('admin' => 'openid_admin_123', 'user1' => 'openid_user1_456');
r($webhookTest->bindTest(1, $validUserList)) && p() && e('1');

// 步骤2：测试空用户列表绑定
$emptyUserList = array();
r($webhookTest->bindTest(1, $emptyUserList)) && p() && e('0');

// 步骤3：测试部分用户ID为空的绑定
$mixedUserList = array('admin' => 'openid_admin_789', 'user2' => '', 'user3' => 'openid_user3_999');
r($webhookTest->bindTest(1, $mixedUserList)) && p() && e('1');

// 步骤4：测试重复绑定相同用户
$duplicateUserList = array('admin' => 'openid_admin_new_111');
r($webhookTest->bindTest(1, $duplicateUserList)) && p() && e('1');

// 步骤5：测试不存在的webhookID绑定
$validUserList2 = array('admin' => 'openid_admin_888');
r($webhookTest->bindTest(999, $validUserList2)) && p() && e('1');