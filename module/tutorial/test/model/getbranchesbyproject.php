#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getBranchesByProject();
timeout=0
cid=19403

- 步骤1：验证返回数组长度 @1
- 步骤2：验证产品ID为1的分支数量 @1
- 步骤3：验证第一个分支的项目ID @2
- 步骤4：验证第一个分支的产品ID @1
- 步骤5：验证第一个分支的分支ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getBranchesByProjectTest())) && p() && e('1'); // 步骤1：验证返回数组长度
r(count($tutorialTest->getBranchesByProjectTest()[1])) && p() && e('1'); // 步骤2：验证产品ID为1的分支数量
r($tutorialTest->getBranchesByProjectTest()[1][0]->project) && p() && e('2'); // 步骤3：验证第一个分支的项目ID
r($tutorialTest->getBranchesByProjectTest()[1][0]->product) && p() && e('1'); // 步骤4：验证第一个分支的产品ID
r($tutorialTest->getBranchesByProjectTest()[1][0]->branch) && p() && e('0'); // 步骤5：验证第一个分支的分支ID