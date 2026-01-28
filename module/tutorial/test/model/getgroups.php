#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getGroups();
timeout=0
cid=19439

- 步骤1：验证返回结果类型为数组 @1
- 步骤2：验证区域1包含3个组 @3
- 步骤3：验证存在第一个组 @1
- 步骤4：验证第一个组ID为1 @1
- 步骤5：验证第三个组order为3 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(is_array($tutorialTest->getGroupsTest())) && p() && e('1'); // 步骤1：验证返回结果类型为数组
r(count($tutorialTest->getGroupsTest()[1])) && p() && e('3'); // 步骤2：验证区域1包含3个组
r(isset($tutorialTest->getGroupsTest()[1][1])) && p() && e('1'); // 步骤3：验证存在第一个组
r($tutorialTest->getGroupsTest()[1][1]->id == 1) && p() && e('1'); // 步骤4：验证第一个组ID为1
r($tutorialTest->getGroupsTest()[1][3]->order == 3) && p() && e('1'); // 步骤5：验证第三个组order为3