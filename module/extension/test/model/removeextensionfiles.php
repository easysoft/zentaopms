#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::removeExtensionFiles();
timeout=0
cid=16469

- 步骤1：空文件列表输入 @0
- 步骤2：空JSON对象 @0
- 步骤3：不存在的文件 @0
- 步骤4：无效JSON格式 @0
- 步骤5：特殊字符文件名 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($extensionTest->removeExtensionFilesTest('')) && p() && e('0'); // 步骤1：空文件列表输入
r($extensionTest->removeExtensionFilesTest('{}')) && p() && e('0'); // 步骤2：空JSON对象
r($extensionTest->removeExtensionFilesTest('{"nonexistent.php":"abc123"}')) && p() && e('0'); // 步骤3：不存在的文件
r($extensionTest->removeExtensionFilesTest('invalid_json')) && p() && e('0'); // 步骤4：无效JSON格式
r($extensionTest->removeExtensionFilesTest('{"test/file@#$.php":"def456"}')) && p() && e('0'); // 步骤5：特殊字符文件名