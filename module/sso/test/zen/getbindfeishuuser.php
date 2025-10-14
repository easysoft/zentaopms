#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::getBindFeishuUser();
cid=0

- 测试步骤1：正常的userToken调用 >> 期望返回失败结果（外部API调用）
- 测试步骤2：空userToken调用 >> 期望返回失败结果
- 测试步骤3：无效userToken调用 >> 期望返回失败结果
- 测试步骤4：空配置对象调用 >> 期望返回失败结果
- 测试步骤5：无效配置ID调用 >> 期望返回失败结果

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sso.unittest.class.php';

zenData('user')->gen(10);
zenData('webhook')->gen(5);

su('admin');

$ssoTest = new ssoTest();

// 测试步骤1：正常的userToken调用（由于是外部API，实际会失败）
$validConfig = new stdClass();
$validConfig->id = 1;
r($ssoTest->getBindFeishuUserTest('valid_user_token', $validConfig)) && p('result') && e('fail');

// 测试步骤2：空userToken调用
$validConfig2 = new stdClass();
$validConfig2->id = 1;
r($ssoTest->getBindFeishuUserTest('', $validConfig2)) && p('result') && e('fail');

// 测试步骤3：无效userToken调用
$validConfig3 = new stdClass();
$validConfig3->id = 1;
r($ssoTest->getBindFeishuUserTest('invalid_token', $validConfig3)) && p('result') && e('fail');

// 测试步骤4：null配置对象调用
r($ssoTest->getBindFeishuUserTest('test_token', null)) && p('result') && e('fail');

// 测试步骤5：无效配置ID调用
$invalidConfig = new stdClass();
$invalidConfig->id = 999;
r($ssoTest->getBindFeishuUserTest('test_token', $invalidConfig)) && p('result') && e('fail');