#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getCases();
timeout=0
cid=19412

- 步骤1：测试返回数组类型 @1
- 步骤2：测试返回数组长度 @1
- 步骤3：测试返回的用例ID第1条的id属性 @1
- 步骤4：测试返回的用例标题第1条的title属性 @Test case
- 步骤5：测试返回的用例状态第1条的status属性 @normal

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$tutorialTest = new tutorialTest();

// 4. 测试步骤
r(is_array($tutorialTest->getCasesTest())) && p() && e(1); // 步骤1：测试返回数组类型
r(count($tutorialTest->getCasesTest())) && p() && e(1); // 步骤2：测试返回数组长度
r($tutorialTest->getCasesTest()) && p('1:id') && e(1); // 步骤3：测试返回的用例ID
r($tutorialTest->getCasesTest()) && p('1:title') && e('Test case'); // 步骤4：测试返回的用例标题
r($tutorialTest->getCasesTest()) && p('1:status') && e('normal'); // 步骤5：测试返回的用例状态