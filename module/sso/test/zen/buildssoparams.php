#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::buildSSOParams();
timeout=0
cid=0

- 步骤1：正常referer参数 @token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com/index.php
- 步骤2：空referer参数 @token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=
- 步骤3：包含特殊字符的referer @token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com/index.php?param=value&test=123
- 步骤4：长referer参数 @token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com/very/long/path/to/some/page/with/many/segments/index.php?param1=value1&param2=value2&param3=value3
- 步骤5：不同token值 @token=different_token_67890&auth=7a6462d2e7bf232ff8b4e087f35ae500&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com/index.php

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sso.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$ssoTest = new ssoTest();

// 4. 测试步骤：必须包含至少5个测试步骤
r($ssoTest->buildSSOParamsTest('http://test.com/index.php')) && p() && e('token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com/index.php'); // 步骤1：正常referer参数
r($ssoTest->buildSSOParamsTest('')) && p() && e('token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer='); // 步骤2：空referer参数
r($ssoTest->buildSSOParamsTest('http://test.com/index.php?param=value&test=123')) && p() && e('token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com/index.php?param=value&test=123'); // 步骤3：包含特殊字符的referer
r($ssoTest->buildSSOParamsTest('http://test.com/very/long/path/to/some/page/with/many/segments/index.php?param1=value1&param2=value2&param3=value3')) && p() && e('token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com/very/long/path/to/some/page/with/many/segments/index.php?param1=value1&param2=value2&param3=value3'); // 步骤4：长referer参数

// 步骤5：测试不同token值
$_GET['token'] = 'different_token_67890';
r($ssoTest->buildSSOParamsTest('http://test.com/index.php')) && p() && e('token=different_token_67890&auth=7a6462d2e7bf232ff8b4e087f35ae500&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com/index.php'); // 步骤5：不同token值