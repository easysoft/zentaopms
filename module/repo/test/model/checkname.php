#!/usr/bin/env php
<?php

/**

title=测试 repoModel::checkName();
timeout=0
cid=18033

- 步骤1：正常英文名称 @1
- 步骤2：数字开头无效 @0
- 步骤3：特殊字符无效 @0
- 步骤4：空字符串边界值 @0
- 步骤5：下划线开头有效 @1
- 步骤6：连字符和点有效 @1
- 步骤7：大写字母有效 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$repoTest = new repoTest();

// 4. 执行测试步骤（至少5个测试步骤）
r($repoTest->checkNameTest('validRepoName')) && p() && e('1');           // 步骤1：正常英文名称
r($repoTest->checkNameTest('123invalid')) && p() && e('0');              // 步骤2：数字开头无效
r($repoTest->checkNameTest('invalid@name')) && p() && e('0');            // 步骤3：特殊字符无效
r($repoTest->checkNameTest('')) && p() && e('0');                        // 步骤4：空字符串边界值
r($repoTest->checkNameTest('_validName')) && p() && e('1');              // 步骤5：下划线开头有效
r($repoTest->checkNameTest('valid-name.test')) && p() && e('1');         // 步骤6：连字符和点有效
r($repoTest->checkNameTest('ValidName123')) && p() && e('1');            // 步骤7：大写字母有效