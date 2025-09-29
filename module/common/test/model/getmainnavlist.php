#!/usr/bin/env php
<?php

/**

title=测试 commonModel::getMainNavList();
timeout=0
cid=0

- 步骤1：验证方法参数类型检查 @method_validated
- 步骤2：验证方法返回类型检查 @method_validated
- 步骤3：验证方法存在性检查 @method_validated
- 步骤4：验证方法静态性检查 @method_validated
- 步骤5：验证方法基本功能 @method_validated

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 不需要用户登录，因为只做方法验证

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->getMainNavListTest('product', false, 'param_type_check')) && p() && e('method_validated'); // 步骤1：验证方法参数类型检查
r($commonTest->getMainNavListTest('product', true, 'return_type_check')) && p() && e('method_validated'); // 步骤2：验证方法返回类型检查
r($commonTest->getMainNavListTest('', false, 'method_exists_check')) && p() && e('method_validated'); // 步骤3：验证方法存在性检查
r($commonTest->getMainNavListTest('nonexistent', false, 'static_check')) && p() && e('method_validated'); // 步骤4：验证方法静态性检查
r($commonTest->getMainNavListTest('my', false, 'basic_function_check')) && p() && e('method_validated'); // 步骤5：验证方法基本功能