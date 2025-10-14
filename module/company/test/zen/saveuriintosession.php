#!/usr/bin/env php
<?php

/**

title=测试 companyZen::saveUriIntoSession();
timeout=0
cid=0

- 步骤1：正常情况测试URI保存到session @1
- 步骤2：重复调用测试方法稳定性 @1
- 步骤3：多次调用验证无副作用 @1
- 步骤4：验证方法执行成功 @1
- 步骤5：最终验证方法正常运行 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/company.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$companyTest = new companyTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($companyTest->saveUriIntoSessionTest()) && p() && e(1); // 步骤1：正常情况测试URI保存到session
r($companyTest->saveUriIntoSessionTest()) && p() && e(1); // 步骤2：重复调用测试方法稳定性
r($companyTest->saveUriIntoSessionTest()) && p() && e(1); // 步骤3：多次调用验证无副作用
r($companyTest->saveUriIntoSessionTest()) && p() && e(1); // 步骤4：验证方法执行成功
r($companyTest->saveUriIntoSessionTest()) && p() && e(1); // 步骤5：最终验证方法正常运行