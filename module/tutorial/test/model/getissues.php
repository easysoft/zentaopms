#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getIssues();
timeout=0
cid=19441

- 步骤1：问题列表数量 @2
- 步骤2：第1个问题ID第1条的id属性 @1
- 步骤3：第1个问题状态第1条的status属性 @unconfirmed
- 步骤4：第2个问题ID第2条的id属性 @2
- 步骤5：第2个问题状态第2条的status属性 @confirmed

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getIssuesTest())) && p() && e('2');                             // 步骤1：问题列表数量
r($tutorialTest->getIssuesTest()) && p('1:id') && e('1');                             // 步骤2：第1个问题ID
r($tutorialTest->getIssuesTest()) && p('1:status') && e('unconfirmed');               // 步骤3：第1个问题状态
r($tutorialTest->getIssuesTest()) && p('2:id') && e('2');                             // 步骤4：第2个问题ID
r($tutorialTest->getIssuesTest()) && p('2:status') && e('confirmed');                 // 步骤5：第2个问题状态