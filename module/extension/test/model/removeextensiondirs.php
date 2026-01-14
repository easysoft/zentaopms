#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::removeExtensionDirs();
timeout=0
cid=16468

- 步骤1：空字符串输入 @0
- 步骤2：不存在的目录JSON列表 @0
- 步骤3：无效JSON格式 @0
- 步骤4：空JSON数组 @0
- 步骤5：null值JSON @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($extensionTest->removeExtensionDirsTest('')) && p() && e('0'); // 步骤1：空字符串输入
r($extensionTest->removeExtensionDirsTest('["nonexistent/dir1", "nonexistent/dir2"]')) && p() && e('0'); // 步骤2：不存在的目录JSON列表
r($extensionTest->removeExtensionDirsTest('invalid json')) && p() && e('0'); // 步骤3：无效JSON格式
r($extensionTest->removeExtensionDirsTest('[]')) && p() && e('0'); // 步骤4：空JSON数组
r($extensionTest->removeExtensionDirsTest('null')) && p() && e('0'); // 步骤5：null值JSON