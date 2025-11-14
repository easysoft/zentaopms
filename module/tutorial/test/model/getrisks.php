#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getRisks();
timeout=0
cid=19470

- 步骤1：获取风险列表，验证数组长度 @1
- 步骤2：验证第一个风险对象的ID第1条的id属性 @1
- 步骤3：验证第一个风险对象的名称第1条的name属性 @Test risk
- 步骤4：验证第一个风险对象的状态第1条的status属性 @active
- 步骤5：验证第一个风险对象的项目ID第1条的project属性 @2

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$tutorialTest = new tutorialTest();

// 4. 执行测试步骤
r(count($tutorialTest->getRisksTest())) && p() && e('1'); // 步骤1：获取风险列表，验证数组长度
r($tutorialTest->getRisksTest()) && p('1:id') && e('1'); // 步骤2：验证第一个风险对象的ID
r($tutorialTest->getRisksTest()) && p('1:name') && e('Test risk'); // 步骤3：验证第一个风险对象的名称
r($tutorialTest->getRisksTest()) && p('1:status') && e('active'); // 步骤4：验证第一个风险对象的状态
r($tutorialTest->getRisksTest()) && p('1:project') && e('2'); // 步骤5：验证第一个风险对象的项目ID