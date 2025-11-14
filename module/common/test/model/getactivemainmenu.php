#!/usr/bin/env php
<?php

/**

title=测试 commonModel::getActiveMainMenu();
timeout=0
cid=15672

- 步骤1：验证方法基本功能-产品模块场景 @method_validated
- 步骤2：验证方法基本功能-空模块场景 @method_validated
- 步骤3：验证方法基本功能-项目模块场景 @method_validated
- 步骤4：验证方法基本功能-执行模块场景 @method_validated
- 步骤5：验证方法基本功能-缺陷模块场景 @method_validated

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->getActiveMainMenuTest(1)) && p() && e('method_validated'); // 步骤1：验证方法基本功能-产品模块场景
r($commonTest->getActiveMainMenuTest(2)) && p() && e('method_validated'); // 步骤2：验证方法基本功能-空模块场景
r($commonTest->getActiveMainMenuTest(3)) && p() && e('method_validated'); // 步骤3：验证方法基本功能-项目模块场景
r($commonTest->getActiveMainMenuTest(4)) && p() && e('method_validated'); // 步骤4：验证方法基本功能-执行模块场景
r($commonTest->getActiveMainMenuTest(5)) && p() && e('method_validated'); // 步骤5：验证方法基本功能-缺陷模块场景