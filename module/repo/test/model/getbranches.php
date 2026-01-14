#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getBranches();
timeout=0
cid=18047

- 步骤1：从SCM获取repo1分支 @0
- 步骤2：从SCM获取repo1分支带标签 @0
- 步骤3：从数据库获取repo1分支属性master @master
- 步骤4：数据库分支带标签前缀属性master @Branch::master
- 步骤5：不存在repo测试 @0
- 步骤6：repo2数据库分支测试属性master @master
- 步骤7：repo2带标签测试属性master @Branch::master

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoModelTest();

// 5. 执行测试步骤（至少7个）
r($repoTest->getBranchesTest(1, false, 'scm')) && p() && e('0'); // 步骤1：从SCM获取repo1分支
r($repoTest->getBranchesTest(1, true, 'scm')) && p() && e('0'); // 步骤2：从SCM获取repo1分支带标签
r($repoTest->getBranchesTest(1, false, 'database')) && p('master') && e('master'); // 步骤3：从数据库获取repo1分支
r($repoTest->getBranchesTest(1, true, 'database')) && p('master') && e('Branch::master'); // 步骤4：数据库分支带标签前缀
r($repoTest->getBranchesTest(999, false, 'scm')) && p() && e('0'); // 步骤5：不存在repo测试
r($repoTest->getBranchesTest(2, false, 'database')) && p('master') && e('master'); // 步骤6：repo2数据库分支测试
r($repoTest->getBranchesTest(2, true, 'database')) && p('master') && e('Branch::master'); // 步骤7：repo2带标签测试