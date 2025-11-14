#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printIcon();
timeout=0
cid=15695

- 步骤1：验证方法存在 @1
- 步骤2：验证方法为静态方法 @1
- 步骤3：验证方法为公共方法 @1
- 步骤4：验证参数数量 @13
- 步骤5：验证方法功能调用buildIconButton @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->printIconTest(1)) && p() && e('1'); // 步骤1：验证方法存在
r($commonTest->printIconTest(2)) && p() && e('1'); // 步骤2：验证方法为静态方法  
r($commonTest->printIconTest(3)) && p() && e('1'); // 步骤3：验证方法为公共方法
r($commonTest->printIconTest(4)) && p() && e('13'); // 步骤4：验证参数数量
r($commonTest->printIconTest(5)) && p() && e('1'); // 步骤5：验证方法功能调用buildIconButton