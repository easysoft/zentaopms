#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getBranches();
timeout=0
cid=19402

- 步骤1：验证返回分支数组长度 @2
- 步骤2：验证第一个分支ID为0 @0
- 步骤3：验证第一个分支名称为主干 @主干
- 步骤4：验证第二个分支ID为1 @1
- 步骤5：验证第二个分支名称为Test branch @Test branch

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getBranchesTest())) && p() && e('2'); // 步骤1：验证返回分支数组长度
r($tutorialTest->getBranchesTest()[0]->id) && p() && e('0'); // 步骤2：验证第一个分支ID为0
r($tutorialTest->getBranchesTest()[0]->name) && p() && e('主干'); // 步骤3：验证第一个分支名称为主干
r($tutorialTest->getBranchesTest()[1]->id) && p() && e('1'); // 步骤4：验证第二个分支ID为1
r($tutorialTest->getBranchesTest()[1]->name) && p() && e('Test branch'); // 步骤5：验证第二个分支名称为Test branch