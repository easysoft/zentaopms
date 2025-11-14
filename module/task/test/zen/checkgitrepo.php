#!/usr/bin/env php
<?php

/**

title=测试 taskZen::checkGitRepo();
timeout=0
cid=18923

- 步骤1：项目1关联产品1，仓库1关联产品1,2，应返回true @1
- 步骤2：项目2关联产品2，仓库1关联产品1,2，应返回true @1
- 步骤3：项目3关联产品3，仓库2关联产品2,3，应返回true @1
- 步骤4：不存在的项目执行ID，应返回false @0
- 步骤5：项目ID为0时获取所有产品，匹配仓库，应返回true @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 直接使用SQL准备数据以确保正确性
global $tester;
$dao = $tester->loadModel('')->dao;

// 清理并插入仓库数据
$dao->exec("DELETE FROM zt_repo");
$dao->exec("INSERT INTO zt_repo (id, product, SCM, name, deleted, synced) VALUES
    (1, '1,2', 'Gitlab', 'repo1', '0', '1'),
    (2, '2,3', 'Gitea', 'repo2', '0', '1'),
    (3, '', 'Gogs', 'repo3', '0', '1')");

// 清理并插入项目产品关联数据
$dao->exec("DELETE FROM zt_projectproduct");
$dao->exec("INSERT INTO zt_projectproduct (project, product, branch) VALUES
    (1, 1, 0),
    (2, 2, 0),
    (3, 3, 0)");

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->checkGitRepoTest(1)) && p() && e(1);     // 步骤1：项目1关联产品1，仓库1关联产品1,2，应返回true
r($taskTest->checkGitRepoTest(2)) && p() && e(1);     // 步骤2：项目2关联产品2，仓库1关联产品1,2，应返回true
r($taskTest->checkGitRepoTest(3)) && p() && e(1);     // 步骤3：项目3关联产品3，仓库2关联产品2,3，应返回true
r($taskTest->checkGitRepoTest(999)) && p() && e(0);   // 步骤4：不存在的项目执行ID，应返回false
r($taskTest->checkGitRepoTest(0)) && p() && e(1);     // 步骤5：项目ID为0时获取所有产品，匹配仓库，应返回true