#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::setNewMark();
timeout=0
cid=0

- 步骤1：非内置透视表 @no_change
- 步骤2：内置透视表版本未变化 @new_label_added
- 步骤3：内置透视表已有标记 @no_change
- 步骤4：内置透视表版本变化 @new_version_label_added
- 步骤5：不在builtins数组中 @no_change

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->setNewMarkTest('not_builtin')) && p() && e('no_change'); // 步骤1：非内置透视表
r($pivotTest->setNewMarkTest('builtin_no_version_change')) && p() && e('new_label_added'); // 步骤2：内置透视表版本未变化
r($pivotTest->setNewMarkTest('builtin_with_mark')) && p() && e('no_change'); // 步骤3：内置透视表已有标记
r($pivotTest->setNewMarkTest('builtin_version_change')) && p() && e('new_version_label_added'); // 步骤4：内置透视表版本变化
r($pivotTest->setNewMarkTest('not_in_builtins')) && p() && e('no_change'); // 步骤5：不在builtins数组中