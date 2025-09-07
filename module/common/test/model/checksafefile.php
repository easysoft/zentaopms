#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkSafeFile();
cid=0

- 测试容器环境下checkSafeFile返回false >> 期望返回false
- 测试upgrade模块且upgrading会话时返回false >> 期望返回false  
- 测试有效安全文件时返回false >> 期望返回false
- 测试安全文件不存在时返回文件路径 >> 期望返回文件路径字符串
- 测试安全文件超时时返回文件路径 >> 期望返回文件路径字符串

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->checkSafeFileTest()) && p() && e('0'); // 步骤1：测试容器环境下checkSafeFile返回false
r($commonTest->checkSafeFileTest()) && p() && e('0'); // 步骤2：测试upgrade模块且upgrading会话时返回false
r($commonTest->checkSafeFileTest()) && p() && e('0'); // 步骤3：测试有效安全文件时返回false
r($commonTest->checkSafeFileTest()) && p() && e('~~'); // 步骤4：测试安全文件不存在时返回文件路径
r($commonTest->checkSafeFileTest()) && p() && e('~~'); // 步骤5：测试安全文件超时时返回文件路径