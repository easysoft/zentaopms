#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getLaneGroup();
timeout=0
cid=19442

- 步骤1：验证返回结果包含3个泳道组 @3
- 步骤2：验证story类型泳道组 @1
- 步骤3：验证task类型泳道组 @1
- 步骤4：验证bug类型泳道组 @1
- 步骤5：验证execution和region属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getLaneGroupTest())) && p() && e('3'); // 步骤1：验证返回结果包含3个泳道组
r($tutorialTest->getLaneGroupTest()[1][0]['type'] == 'story') && p() && e('1'); // 步骤2：验证story类型泳道组
r($tutorialTest->getLaneGroupTest()[2][0]['type'] == 'task') && p() && e('1'); // 步骤3：验证task类型泳道组
r($tutorialTest->getLaneGroupTest()[3][0]['type'] == 'bug') && p() && e('1'); // 步骤4：验证bug类型泳道组
r($tutorialTest->getLaneGroupTest()[1][0]['execution'] == 3 && $tutorialTest->getLaneGroupTest()[1][0]['region'] == 1) && p() && e('1'); // 步骤5：验证execution和region属性