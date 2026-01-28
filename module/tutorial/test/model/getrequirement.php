#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getRequirement();
timeout=0
cid=19463

- 步骤1：验证用户需求是否为父级属性isParent @1
- 步骤2：验证用户需求ID属性id @2
- 步骤3：验证用户需求类型属性type @requirement
- 步骤4：验证用户需求标题属性title @Test requirement
- 步骤5：验证用户需求状态属性status @active

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getRequirementTest()) && p('isParent') && e('1'); // 步骤1：验证用户需求是否为父级
r($tutorialTest->getRequirementTest()) && p('id') && e('2'); // 步骤2：验证用户需求ID
r($tutorialTest->getRequirementTest()) && p('type') && e('requirement'); // 步骤3：验证用户需求类型
r($tutorialTest->getRequirementTest()) && p('title') && e('Test requirement'); // 步骤4：验证用户需求标题
r($tutorialTest->getRequirementTest()) && p('status') && e('active'); // 步骤5：验证用户需求状态