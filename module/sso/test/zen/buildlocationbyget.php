#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::buildLocationByGET();
timeout=0
cid=0

- 步骤1：正常路径转换 @/zentao/index.php?m=product&f=browse&token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com
- 步骤2：已包含GET参数的URL @/zentao/index.php?m=task&f=view&token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com
- 步骤3：复杂路径转换 @/zentao/index.php?m=task&f=view&token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com
- 步骤4：根路径转换 @/index.php?m=index&f=index&token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com
- 步骤5：项目模块路径转换 @/zentao/index.php?m=project&f=execution&token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sso.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$ssoTest = new ssoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($ssoTest->buildLocationByGETTest('/zentao/product-browse.html', 'http://test.com')) && p() && e('/zentao/index.php?m=product&f=browse&token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com'); // 步骤1：正常路径转换
r($ssoTest->buildLocationByGETTest('/zentao/index.php?m=task&f=view', 'http://test.com')) && p() && e('/zentao/index.php?m=task&f=view&token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com'); // 步骤2：已包含GET参数的URL
r($ssoTest->buildLocationByGETTest('/zentao/task-view.html', 'http://test.com')) && p() && e('/zentao/index.php?m=task&f=view&token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com'); // 步骤3：复杂路径转换
r($ssoTest->buildLocationByGETTest('/index-index.html', 'http://test.com')) && p() && e('/index.php?m=index&f=index&token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com'); // 步骤4：根路径转换
r($ssoTest->buildLocationByGETTest('/zentao/project-execution.html', 'http://test.com')) && p() && e('/zentao/index.php?m=project&f=execution&token=test_token_12345&auth=c08056e83c5d8bf81be65d50eedbc5ab&userIP=127.0.0.1&callback=http%3A%2F%2Ftest.com%2Fsso-login-type-return.html&referer=http://test.com'); // 步骤5：项目模块路径转换