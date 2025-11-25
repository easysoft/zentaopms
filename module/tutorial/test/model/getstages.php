#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getStages();
timeout=0
cid=19473

- 步骤1：验证返回数组包含ID为3的阶段第3条的id属性 @3
- 步骤2：验证阶段名称第3条的name属性 @Development stage
- 步骤3：验证阶段类型第3条的type属性 @dev
- 步骤4：验证阶段百分比第3条的percent属性 @50
- 步骤5：验证阶段项目类型第3条的projectType属性 @waterfall

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getStagesTest()) && p('3:id') && e('3'); // 步骤1：验证返回数组包含ID为3的阶段
r($tutorialTest->getStagesTest()) && p('3:name') && e('Development stage'); // 步骤2：验证阶段名称
r($tutorialTest->getStagesTest()) && p('3:type') && e('dev'); // 步骤3：验证阶段类型
r($tutorialTest->getStagesTest()) && p('3:percent') && e('50'); // 步骤4：验证阶段百分比
r($tutorialTest->getStagesTest()) && p('3:projectType') && e('waterfall'); // 步骤5：验证阶段项目类型