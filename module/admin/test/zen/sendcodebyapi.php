#!/usr/bin/env php
<?php

/**

title=测试 adminZen::sendCodeByAPI();
timeout=0
cid=0

- 步骤1：mobile类型 @1
- 步骤2：email类型 @1
- 步骤3：空字符串 @1
- 步骤4：无效类型 @1
- 步骤5：sms类型 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$adminTest = new adminTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($adminTest->sendCodeByAPITest('mobile')) && p() && e('1'); // 步骤1：mobile类型
r($adminTest->sendCodeByAPITest('email')) && p() && e('1'); // 步骤2：email类型
r($adminTest->sendCodeByAPITest('')) && p() && e('1'); // 步骤3：空字符串
r($adminTest->sendCodeByAPITest('invalid')) && p() && e('1'); // 步骤4：无效类型
r($adminTest->sendCodeByAPITest('sms')) && p() && e('1'); // 步骤5：sms类型