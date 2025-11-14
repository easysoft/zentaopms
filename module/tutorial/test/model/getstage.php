#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getStage();
timeout=0
cid=19472

- 步骤1：验证stage的id属性属性id @3
- 步骤2：验证stage的name属性属性name @Development stage
- 步骤3：验证stage的percent属性属性percent @50
- 步骤4：验证stage的type属性属性type @dev
- 步骤5：验证stage的projectType属性属性projectType @waterfall

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 执行测试步骤（至少5个）
r($tutorialTest->getStageTest()) && p('id') && e('3'); // 步骤1：验证stage的id属性
r($tutorialTest->getStageTest()) && p('name') && e('Development stage'); // 步骤2：验证stage的name属性
r($tutorialTest->getStageTest()) && p('percent') && e('50'); // 步骤3：验证stage的percent属性
r($tutorialTest->getStageTest()) && p('type') && e('dev'); // 步骤4：验证stage的type属性
r($tutorialTest->getStageTest()) && p('projectType') && e('waterfall'); // 步骤5：验证stage的projectType属性