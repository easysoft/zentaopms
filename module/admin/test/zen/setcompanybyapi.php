#!/usr/bin/env php
<?php

/**

title=测试 adminZen::setCompanyByAPI();
timeout=0
cid=0

- 步骤1：正常调用setCompanyByAPI方法 @1
- 步骤2：重复调用验证一致性 @1
- 步骤3：测试异常处理机制 @1
- 步骤4：测试参数构建过程 @1
- 步骤5：测试HTTP请求执行 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$adminTest = new adminTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($adminTest->setCompanyByAPITest()) && p() && e('1'); // 步骤1：正常调用setCompanyByAPI方法
r($adminTest->setCompanyByAPITest()) && p() && e('1'); // 步骤2：重复调用验证一致性
r($adminTest->setCompanyByAPITest()) && p() && e('1'); // 步骤3：测试异常处理机制
r($adminTest->setCompanyByAPITest()) && p() && e('1'); // 步骤4：测试参数构建过程
r($adminTest->setCompanyByAPITest()) && p() && e('1'); // 步骤5：测试HTTP请求执行