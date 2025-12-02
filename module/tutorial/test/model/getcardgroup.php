#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getCardGroup();
timeout=0
cid=19410

- 步骤1：正常获取卡片组，验证返回数组的顶级键数量 @3
- 步骤2：验证story卡片类型 @story
- 步骤3：验证task卡片类型 @task
- 步骤4：验证bug卡片类型 @bug
- 步骤5：验证story卡片标题 @Test story

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
$cardGroupResult = $tutorialTest->getCardGroupTest();
r(count($cardGroupResult)) && p() && e('3'); // 步骤1：正常获取卡片组，验证返回数组的顶级键数量
r($cardGroupResult[1][1][1][0]['cardType']) && p() && e('story'); // 步骤2：验证story卡片类型
r($cardGroupResult[2][2][16][0]['cardType']) && p() && e('task'); // 步骤3：验证task卡片类型
r($cardGroupResult[3][3][23][0]['cardType']) && p() && e('bug'); // 步骤4：验证bug卡片类型
r($cardGroupResult[1][1][1][0]['title']) && p() && e('Test story'); // 步骤5：验证story卡片标题