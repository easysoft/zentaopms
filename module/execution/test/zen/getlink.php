#!/usr/bin/env php
<?php

/**

title=测试 executionZen::getLink();
timeout=0
cid=16430

- 步骤1：测试执行模块创建方法返回空字符串 @0
- 步骤2：测试任务模块视图方法返回task @task
- 步骤3：测试用例创建方法返回testcase @testcase
- 步骤4：测试需求视图方法返回story @story
- 步骤5：测试测试单编辑方法返回testtask @testtask

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($executionTest->getLinkTest('execution', 'create')) && p() && e('0'); // 步骤1：测试执行模块创建方法返回空字符串
r($executionTest->getLinkTest('task', 'view')) && p() && e('task'); // 步骤2：测试任务模块视图方法返回task
r($executionTest->getLinkTest('testcase', 'create')) && p() && e('testcase'); // 步骤3：测试用例创建方法返回testcase
r($executionTest->getLinkTest('story', 'view')) && p() && e('story'); // 步骤4：测试需求视图方法返回story
r($executionTest->getLinkTest('testtask', 'edit')) && p() && e('testtask'); // 步骤5：测试测试单编辑方法返回testtask