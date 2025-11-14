#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getResearchStageStats();
timeout=0
cid=19464

- 步骤1：获取调研阶段统计数据，期望返回3个元素 @3
- 步骤2：验证第一个元素的type属性第0条的type属性 @stage
- 步骤3：验证第一个元素的attribute属性第0条的attribute属性 @research
- 步骤4：验证第二个元素的type属性第1条的type属性 @research
- 步骤5：验证第三个元素的status属性第2条的status属性 @done

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getResearchStageStatsTest())) && p() && e('3'); // 步骤1：获取调研阶段统计数据，期望返回3个元素
r($tutorialTest->getResearchStageStatsTest()) && p('0:type') && e('stage'); // 步骤2：验证第一个元素的type属性
r($tutorialTest->getResearchStageStatsTest()) && p('0:attribute') && e('research'); // 步骤3：验证第一个元素的attribute属性
r($tutorialTest->getResearchStageStatsTest()) && p('1:type') && e('research'); // 步骤4：验证第二个元素的type属性
r($tutorialTest->getResearchStageStatsTest()) && p('2:status') && e('done'); // 步骤5：验证第三个元素的status属性