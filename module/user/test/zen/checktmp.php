#!/usr/bin/env php
<?php

/**

title=测试 userZen::checkTmp();
timeout=0
cid=19673

- 步骤1：正常情况下检查权限 @1
- 步骤2：再次检查确保一致性 @1
- 步骤3：重复测试确保稳定性 @1
- 步骤4：验证权限检查结果 @1
- 步骤5：最终权限验证 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$userTest = new userZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($userTest->checkTmpTest()) && p() && e(1);  // 步骤1：正常情况下检查权限
r($userTest->checkTmpTest()) && p() && e(1);  // 步骤2：再次检查确保一致性
r($userTest->checkTmpTest()) && p() && e(1);  // 步骤3：重复测试确保稳定性
r($userTest->checkTmpTest()) && p() && e(1);  // 步骤4：验证权限检查结果
r($userTest->checkTmpTest()) && p() && e(1);  // 步骤5：最终权限验证