#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::computeAuth();
timeout=0
cid=0

- 步骤1：正常token @1038b7f7125511be05937586fc07618d
- 步骤2：空token @9bf5b4bd2824251d5c55bb5029fc3c09
- 步骤3：特殊字符token @1329c8b8eda0e92e8e767e04285382b4
- 步骤4：长token @0a3de0755aefbf9bdff3ef0665ea5a3f
- 步骤5：数字token @be1be0b3cf81ff1cf28673f1c9d078db

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sso.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$ssoTest = new ssoTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($ssoTest->computeAuthTest('token123')) && p() && e('1038b7f7125511be05937586fc07618d'); // 步骤1：正常token
r($ssoTest->computeAuthTest('')) && p() && e('9bf5b4bd2824251d5c55bb5029fc3c09'); // 步骤2：空token
r($ssoTest->computeAuthTest('!@#$%^&*()')) && p() && e('1329c8b8eda0e92e8e767e04285382b4'); // 步骤3：特殊字符token
r($ssoTest->computeAuthTest('very_long_token_string_with_many_characters_1234567890')) && p() && e('0a3de0755aefbf9bdff3ef0665ea5a3f'); // 步骤4：长token
r($ssoTest->computeAuthTest('123456789')) && p() && e('be1be0b3cf81ff1cf28673f1c9d078db'); // 步骤5：数字token