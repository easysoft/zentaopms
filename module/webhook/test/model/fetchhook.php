#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::fetchHook();
timeout=0
cid=19688

- 步骤1：测试钉钉用户webhook类型，无绑定用户 @Could not resolve host
- 步骤2：测试微信用户webhook类型，无绑定用户 @false
- 步骤3：测试飞书用户webhook类型，无绑定用户 @false
- 步骤4：测试普通webhook无效URL情况 @false
- 步骤5：测试钉钉群组webhook正常情况 @{"errcode":0,"errmsg":"ok"}

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$webhookTest = new webhookModelTest();

r($webhookTest->fetchHookTestError('invalid_url', 'test')) && p() && e('Could not resolve host'); // 步骤1：测试钉钉用户webhook类型，无绑定用户
r($webhookTest->fetchHookTestMock('dinguser', 'test')) && p() && e('false'); // 步骤2：测试微信用户webhook类型，无绑定用户
r($webhookTest->fetchHookTestMock('wechatuser', 'test')) && p() && e('false'); // 步骤3：测试飞书用户webhook类型，无绑定用户
r($webhookTest->fetchHookTestMock('feishuuser', 'test')) && p() && e('false'); // 步骤4：测试普通webhook无效URL情况
r($webhookTest->fetchHookTestMock('success', 'test')) && p() && e('{"errcode":0,"errmsg":"ok"}'); // 步骤5：测试钉钉群组webhook正常情况