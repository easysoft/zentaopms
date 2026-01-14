#!/usr/bin/env php
<?php

/**

title=测试 commonModel::setMainMenu();
timeout=0
cid=15711

- 步骤1：验证方法存在性 @method_exists
- 步骤2：验证方法为静态方法 @is_static
- 步骤3：验证返回类型为bool @return_bool
- 步骤4：验证无参数要求 @no_parameters
- 步骤5：验证方法为公共可访问 @is_public

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($commonTest->setMainMenuTest(1)) && p() && e('method_exists'); // 步骤1：验证方法存在性
r($commonTest->setMainMenuTest(2)) && p() && e('is_static'); // 步骤2：验证方法为静态方法
r($commonTest->setMainMenuTest(3)) && p() && e('return_bool'); // 步骤3：验证返回类型为bool
r($commonTest->setMainMenuTest(4)) && p() && e('no_parameters'); // 步骤4：验证无参数要求
r($commonTest->setMainMenuTest(5)) && p() && e('is_public'); // 步骤5：验证方法为公共可访问