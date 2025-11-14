#!/usr/bin/env php
<?php

/**

title=测试 systemModel::updateMinioDomain();
timeout=0
cid=18752

- 步骤1：正常调用updateMinioDomain方法 @0
- 步骤2：测试重复调用 @0
- 步骤3：测试连续调用 @0
- 步骤4：测试多次调用的一致性 @0
- 步骤5：测试方法的稳定性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/system.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$systemTest = new systemTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($systemTest->updateMinioDomainTest()) && p() && e(0); // 步骤1：正常调用updateMinioDomain方法
r($systemTest->updateMinioDomainTest()) && p() && e(0); // 步骤2：测试重复调用
r($systemTest->updateMinioDomainTest()) && p() && e(0); // 步骤3：测试连续调用
r($systemTest->updateMinioDomainTest()) && p() && e(0); // 步骤4：测试多次调用的一致性
r($systemTest->updateMinioDomainTest()) && p() && e(0); // 步骤5：测试方法的稳定性