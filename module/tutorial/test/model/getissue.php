#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getIssue();
timeout=0
cid=19440

- 步骤1：验证问题ID属性id @1
- 步骤2：验证问题标题属性title @Test issue-unconfirmed
- 步骤3：验证问题状态属性status @unconfirmed
- 步骤4：验证问题优先级属性pri @3
- 步骤5：验证问题类型属性type @design

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getIssueTest()) && p('id') && e('1');                                    // 步骤1：验证问题ID
r($tutorialTest->getIssueTest()) && p('title') && e('Test issue-unconfirmed');           // 步骤2：验证问题标题
r($tutorialTest->getIssueTest()) && p('status') && e('unconfirmed');                     // 步骤3：验证问题状态
r($tutorialTest->getIssueTest()) && p('pri') && e('3');                                  // 步骤4：验证问题优先级
r($tutorialTest->getIssueTest()) && p('type') && e('design');                            // 步骤5：验证问题类型