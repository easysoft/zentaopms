#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::buildLocationByPATHINFO();
timeout=0
cid=0

- 步骤1：正常GET格式URL转换 @/zentao/user-profile.html?token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com
- 步骤2：复杂参数GET格式URL转换 @/zentao/project-browse.html?token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com/referer
- 步骤3：PATH_INFO格式URL直接处理 @/zentao/user-profile.html?token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com
- 步骤4：无效格式URL处理 @/zentao/invalid-url?token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com
- 步骤5：空字符串输入处理 @?token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sso.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$ssoTest = new ssoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($ssoTest->buildLocationByPATHINFOTest('/zentao/index.php?m=user&f=profile', 'http://test.com')) && p() && e('/zentao/user-profile.html?token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com'); // 步骤1：正常GET格式URL转换

r($ssoTest->buildLocationByPATHINFOTest('/zentao/index.php?m=project&f=browse&projectID=1', 'http://test.com/referer')) && p() && e('/zentao/project-browse.html?token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com/referer'); // 步骤2：复杂参数GET格式URL转换

r($ssoTest->buildLocationByPATHINFOTest('/zentao/user-profile.html', 'http://test.com')) && p() && e('/zentao/user-profile.html?token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com'); // 步骤3：PATH_INFO格式URL直接处理

r($ssoTest->buildLocationByPATHINFOTest('/zentao/invalid-url', 'http://test.com')) && p() && e('/zentao/invalid-url?token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com'); // 步骤4：无效格式URL处理

r($ssoTest->buildLocationByPATHINFOTest('', 'http://test.com')) && p() && e('?token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com'); // 步骤5：空字符串输入处理