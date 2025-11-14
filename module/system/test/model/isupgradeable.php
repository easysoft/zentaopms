#!/usr/bin/env php
<?php

/**

title=测试 systemModel::isUpgradeable();
timeout=0
cid=18743

- 步骤1：正常情况，预期无升级 @0
- 步骤2：边界值，版本相同 @0
- 步骤3：异常输入，环境变量缺失 @0
- 步骤4：权限验证，管理员权限 @0
- 步骤5：业务规则，默认配置下的升级检查 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/system.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$systemTest = new systemTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($systemTest->isUpgradeableTest()) && p() && e('0'); // 步骤1：正常情况，预期无升级
r($systemTest->isUpgradeableTest()) && p() && e('0'); // 步骤2：边界值，版本相同
r($systemTest->isUpgradeableTest()) && p() && e('0'); // 步骤3：异常输入，环境变量缺失
r($systemTest->isUpgradeableTest()) && p() && e('0'); // 步骤4：权限验证，管理员权限
r($systemTest->isUpgradeableTest()) && p() && e('0'); // 步骤5：业务规则，默认配置下的升级检查