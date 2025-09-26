#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printCommentIcon();
timeout=0
cid=0

- 步骤1：验证方法存在性 @1
- 步骤2：验证方法是静态方法 @1
- 步骤3：验证方法参数数量 @2
- 步骤4：验证第一个参数类型为string @string
- 步骤5：验证第二个参数可为null @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 3. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->printCommentIconTest('method_exists')) && p() && e('1'); // 步骤1：验证方法存在性
r($commonTest->printCommentIconTest('is_static')) && p() && e('1'); // 步骤2：验证方法是静态方法
r($commonTest->printCommentIconTest('param_count')) && p() && e('2'); // 步骤3：验证方法参数数量
r($commonTest->printCommentIconTest('first_param_type')) && p() && e('string'); // 步骤4：验证第一个参数类型为string
r($commonTest->printCommentIconTest('second_param_nullable')) && p() && e('1'); // 步骤5：验证第二个参数可为null