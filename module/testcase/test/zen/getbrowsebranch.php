#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getBrowseBranch();
timeout=0
cid=0

- 步骤1：空字符串时返回cookie中的preBranch @test_branch
- 步骤2：非空字符串直接返回 @main
- 步骤3：空字符串且preBranch也为空时返回0 @0
- 步骤4：另一个非空字符串测试 @develop
- 步骤5：包含特殊字符的分支名 @feature/test

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->getBrowseBranchTest('', 'test_branch')) && p() && e('test_branch'); // 步骤1：空字符串时返回cookie中的preBranch
r($testcaseTest->getBrowseBranchTest('main', 'test_branch')) && p() && e('main'); // 步骤2：非空字符串直接返回
r($testcaseTest->getBrowseBranchTest('', '')) && p() && e('0'); // 步骤3：空字符串且preBranch也为空时返回0
r($testcaseTest->getBrowseBranchTest('develop', 'test_branch')) && p() && e('develop'); // 步骤4：另一个非空字符串测试
r($testcaseTest->getBrowseBranchTest('feature/test', 'test_branch')) && p() && e('feature/test'); // 步骤5：包含特殊字符的分支名