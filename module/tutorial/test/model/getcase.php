#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getCase();
timeout=0
cid=19411

- 步骤1：验证用例ID属性id @1
- 步骤2：验证用例标题属性title @Test case
- 步骤3：验证用例状态属性status @normal
- 步骤4：验证用例类型属性type @feature
- 步骤5：验证测试步骤数量属性stepNumber @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getCaseTest()) && p('id') && e('1'); // 步骤1：验证用例ID
r($tutorialTest->getCaseTest()) && p('title') && e('Test case'); // 步骤2：验证用例标题
r($tutorialTest->getCaseTest()) && p('status') && e('normal'); // 步骤3：验证用例状态
r($tutorialTest->getCaseTest()) && p('type') && e('feature'); // 步骤4：验证用例类型
r($tutorialTest->getCaseTest()) && p('stepNumber') && e('1'); // 步骤5：验证测试步骤数量