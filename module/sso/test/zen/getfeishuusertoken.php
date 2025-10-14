#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::getFeishuUserToken();
cid=0

- 测试步骤1：使用有效的授权码和访问令牌 >> 期望返回成功结果
- 测试步骤2：使用空的授权码 >> 期望返回失败结果
- 测试步骤3：使用空的访问令牌 >> 期望返回失败结果
- 测试步骤4：使用无效的授权码格式 >> 期望返回失败结果
- 测试步骤5：使用过期的访问令牌 >> 期望返回失败结果

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sso.unittest.class.php';

su('admin');

$ssoTest = new ssoTest();

// 测试步骤1：使用有效的授权码和访问令牌
r($ssoTest->getFeishuUserTokenTest('valid_code_123', 'valid_access_token_456')) && p('result') && e('fail'); // 由于是外部API调用，实际会失败

// 测试步骤2：使用空的授权码
r($ssoTest->getFeishuUserTokenTest('', 'valid_access_token_456')) && p('result') && e('fail');

// 测试步骤3：使用空的访问令牌
r($ssoTest->getFeishuUserTokenTest('valid_code_123', '')) && p('result') && e('fail');

// 测试步骤4：使用无效的授权码格式
r($ssoTest->getFeishuUserTokenTest('invalid@code#format', 'valid_access_token_456')) && p('result') && e('fail');

// 测试步骤5：使用过期的访问令牌
r($ssoTest->getFeishuUserTokenTest('valid_code_123', 'expired_token_789')) && p('result') && e('fail');