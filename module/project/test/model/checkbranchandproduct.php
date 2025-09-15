#!/usr/bin/env php
<?php

/**

title=测试 projectModel::checkBranchAndProduct();
timeout=0
cid=0

- 步骤1：正常情况-多分支产品提供正确分支 @rue
- 步骤2：边界值-空产品数组 @rue
- 步骤3：异常输入-多分支产品未提供分支属性branch[0][] @分支不能为空。
- 步骤4：异常输入-分支ID为空字符串属性branch[0][] @分支不能为空。
- 步骤5：正常情况-非多分支产品 @rue

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备
zenData('project')->gen(10);
zenData('product')->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new Project();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->checkBranchAndProductTest(1, array(1, 2), array(array(1), array(2)))) && p() && e(true); // 步骤1：正常情况-有产品有分支
r($projectTest->checkBranchAndProductTest(1, array(), array())) && p() && e(true); // 步骤2：边界值-空产品数组
r($projectTest->checkBranchAndProductTest(0, array(1), array(array('')))) && p('branch[0][]') && e('分支不能为空。'); // 步骤3：异常输入-分支ID为空字符串
r($projectTest->checkBranchAndProductTest(1, array(3, 4), array(array(''), array('')))) && p('branch[0][]') && e('分支不能为空。'); // 步骤4：异常输入-多产品分支为空
r($projectTest->checkBranchAndProductTest(2, array(1, 2, 3), array(array(1), array(2), array(3)))) && p() && e(true); // 步骤5：正常情况-多产品多分支