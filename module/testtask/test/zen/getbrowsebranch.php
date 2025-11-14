#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::getBrowseBranch();
timeout=0
cid=19234

- 执行testtaskTest模块的getBrowseBranchTest方法，参数是'master', 'normal'  @all
- 执行testtaskTest模块的getBrowseBranchTest方法，参数是'', 'multi'  @0
- 执行testtaskTest模块的getBrowseBranchTest方法，参数是'develop', 'multi'  @develop
- 执行testtaskTest模块的getBrowseBranchTest方法，参数是'123', 'branch'  @123
- 执行testtaskTest模块的getBrowseBranchTest方法，参数是'feature-v1.0', 'branch'  @feature-v1.0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 模拟用户登录
su('admin');

// 清理 cookie 以确保测试环境干净
helper::setcookie('preBranch', '');

// 创建测试实例
$testtaskTest = new testtaskZenTest();

// 测试步骤1：正常产品类型，应该返回 'all'
r($testtaskTest->getBrowseBranchTest('master', 'normal')) && p() && e('all');

// 测试步骤2：多分支产品类型，空分支，应该返回空字符串
r($testtaskTest->getBrowseBranchTest('', 'multi')) && p() && e('0');

// 测试步骤3：多分支产品类型，指定分支，应该返回指定分支
r($testtaskTest->getBrowseBranchTest('develop', 'multi')) && p() && e('develop');

// 测试步骤4：多分支产品类型，数字分支，应该返回数字分支
r($testtaskTest->getBrowseBranchTest('123', 'branch')) && p() && e('123');

// 测试步骤5：多分支产品类型，带特殊字符的分支，应该返回带特殊字符的分支
r($testtaskTest->getBrowseBranchTest('feature-v1.0', 'branch')) && p() && e('feature-v1.0');