#!/usr/bin/env php
<?php

/**

title=测试 repoModel::setHideMenu();
timeout=0
cid=0

- 步骤1：有代码库时显示标签菜单第tag条的link属性 @标签|repo|browsetag|repoID=0&objectID=%s
- 步骤2：无代码库时不显示标签菜单属性tag @0
- 步骤3：有代码库时显示MR菜单第mr条的link属性 @合并请求|mr|browse|repoID=0&mode=status&param=opened&objectID=%s
- 步骤4：project环境下显示提交菜单第commit条的link属性 @提交|repo|log|repoID=0&branchID=&objectID=%s
- 步骤5：有代码库时显示评审菜单第review条的link属性 @评审|repo|review|repoID=0&objectID=%s
- 步骤6：waterfall环境下显示分支菜单第branch条的link属性 @分支|repo|browsebranch|repoID=0&objectID=%s
- 步骤7：正常情况菜单存在属性repo @~~
- 步骤8：验证提交菜单存在属性commit @~~
- 步骤9：验证基础菜单存在第repo条的link属性 @代码库|repo|browse|repoID=0&branchID=&objectID=%s
- 步骤10：验证完整菜单结构存在
 - 属性tag @~~
 - 属性branch @~~
 - 属性mr @~~
 - 属性review @~~
 - 属性commit @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. zendata数据准备
zenData('repo')->loadYaml('repo')->gen(10);
zenData('project')->loadYaml('execution')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('execution', 101)) && p('tag:link') && e('标签|repo|browsetag|repoID=0&objectID=%s'); // 步骤1：有代码库时显示标签菜单

$tester->session->set('repoID', 0);
r($repoTest->setHideMenuTest('execution', 102)) && p('tag') && e('0'); // 步骤2：无代码库时不显示标签菜单

$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('execution', 103)) && p('mr:link') && e('合并请求|mr|browse|repoID=0&mode=status&param=opened&objectID=%s'); // 步骤3：有代码库时显示MR菜单

$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('project', 104)) && p('commit:link') && e('提交|repo|log|repoID=0&branchID=&objectID=%s'); // 步骤4：project环境下显示提交菜单

$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('execution', 105)) && p('review:link') && e('评审|repo|review|repoID=0&objectID=%s'); // 步骤5：有代码库时显示评审菜单

$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('waterfall', 106)) && p('branch:link') && e('分支|repo|browsebranch|repoID=0&objectID=%s'); // 步骤6：waterfall环境下显示分支菜单

$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('execution', 107)) && p('repo') && e('~~'); // 步骤7：正常情况菜单存在

$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('execution', 108)) && p('commit') && e('~~'); // 步骤8：验证提交菜单存在

$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('execution', 109)) && p('repo:link') && e('代码库|repo|browse|repoID=0&branchID=&objectID=%s'); // 步骤9：验证基础菜单存在

$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('execution', 110)) && p('tag,branch,mr,review,commit') && e('~~,~~,~~,~~,~~'); // 步骤10：验证完整菜单结构存在