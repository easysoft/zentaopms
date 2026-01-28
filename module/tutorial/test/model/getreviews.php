#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getReviews();
timeout=0
cid=19468

- 步骤1：正常调用返回数组长度 @1
- 步骤2：验证评审ID第1条的id属性 @1
- 步骤3：验证评审标题第1条的title属性 @Test Review
- 步骤4：验证评审状态第1条的status属性 @pass
- 步骤5：验证评审产品ID第1条的product属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getReviewsTest())) && p() && e('1'); // 步骤1：正常调用返回数组长度
r($tutorialTest->getReviewsTest()) && p('1:id') && e('1'); // 步骤2：验证评审ID
r($tutorialTest->getReviewsTest()) && p('1:title') && e('Test Review'); // 步骤3：验证评审标题
r($tutorialTest->getReviewsTest()) && p('1:status') && e('pass'); // 步骤4：验证评审状态
r($tutorialTest->getReviewsTest()) && p('1:product') && e('1'); // 步骤5：验证评审产品ID