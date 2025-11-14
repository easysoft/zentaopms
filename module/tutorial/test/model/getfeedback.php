#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getFeedback();
timeout=0
cid=19436

- 步骤1：验证反馈ID为1 @1
- 步骤2：验证反馈标题为Test feedback @Test feedback
- 步骤3：验证反馈状态为noreview @noreview
- 步骤4：验证反馈优先级为3 @3
- 步骤5：验证反馈产品ID为1 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getFeedbackTest()->id) && p() && e('1'); // 步骤1：验证反馈ID为1
r($tutorialTest->getFeedbackTest()->title) && p() && e('Test feedback'); // 步骤2：验证反馈标题为Test feedback
r($tutorialTest->getFeedbackTest()->status) && p() && e('noreview'); // 步骤3：验证反馈状态为noreview
r($tutorialTest->getFeedbackTest()->pri) && p() && e('3'); // 步骤4：验证反馈优先级为3
r($tutorialTest->getFeedbackTest()->product) && p() && e('1'); // 步骤5：验证反馈产品ID为1