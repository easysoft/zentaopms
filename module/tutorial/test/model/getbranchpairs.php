#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getBranchPairs();
timeout=0
cid=19404

- 步骤1：验证主分支名称（ID=0） @主干
- 步骤2：验证测试分支名称（ID=1）属性1 @Test branch
- 步骤3：验证返回数组长度 @2
- 步骤4：验证第一个键为0 @0
- 步骤5：验证返回类型为数组 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getBranchPairsTest()) && p('0') && e('主干'); // 步骤1：验证主分支名称（ID=0）
r($tutorialTest->getBranchPairsTest()) && p('1') && e('Test branch'); // 步骤2：验证测试分支名称（ID=1）
r(count($tutorialTest->getBranchPairsTest())) && p() && e('2'); // 步骤3：验证返回数组长度
r(array_keys($tutorialTest->getBranchPairsTest())) && p('0') && e('0'); // 步骤4：验证第一个键为0
r(is_array($tutorialTest->getBranchPairsTest())) && p() && e('1'); // 步骤5：验证返回类型为数组