#!/usr/bin/env php
<?php

/**

title=测试 projectModel::checkBranchAndProduct();
timeout=0
cid=17805

- 步骤1：正常情况-有产品有分支 @1
- 步骤2：边界值-空产品数组 @1
- 步骤3：异常输入-分支ID为空字符串属性branch[0][] @1
- 步骤4：异常输入-多产品分支为空属性branch[0][] @1
- 步骤5：正常情况-多产品多分支 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zenData('project')->gen(10);
zenData('product')->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->checkBranchAndProductTest(1, array(1, 2), array(array(1), array(2))))              && p()              && e('1'); // 步骤1：正常情况-有产品有分支
r($projectTest->checkBranchAndProductTest(1, array(), array()))                                    && p()              && e('1'); // 步骤2：边界值-空产品数组
r($projectTest->checkBranchAndProductTest(0, array(1), array(array(''))))                          && p('branch[0][]') && e('1'); // 步骤3：异常输入-分支ID为空字符串
r($projectTest->checkBranchAndProductTest(1, array(3, 4), array(array(''), array(''))))            && p('branch[0][]') && e('1'); // 步骤4：异常输入-多产品分支为空
r($projectTest->checkBranchAndProductTest(2, array(1, 2, 3), array(array(1), array(2), array(3)))) && p()              && e('1'); // 步骤5：正常情况-多产品多分支
