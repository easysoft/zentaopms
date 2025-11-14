#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getTestReports();
timeout=0
cid=19489

- 步骤1：验证返回数组包含1个元素 @1
- 步骤2：验证包含ID为1的测试报告第1条的id属性 @1
- 步骤3：验证测试报告标题第1条的title属性 @Test testreport
- 步骤4：验证对象类型第1条的objectType属性 @execution
- 步骤5：验证对象ID第1条的objectID属性 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getTestReportsTest())) && p() && e('1'); // 步骤1：验证返回数组包含1个元素
r($tutorialTest->getTestReportsTest()) && p('1:id') && e('1'); // 步骤2：验证包含ID为1的测试报告
r($tutorialTest->getTestReportsTest()) && p('1:title') && e('Test testreport'); // 步骤3：验证测试报告标题
r($tutorialTest->getTestReportsTest()) && p('1:objectType') && e('execution'); // 步骤4：验证对象类型
r($tutorialTest->getTestReportsTest()) && p('1:objectID') && e('3'); // 步骤5：验证对象ID