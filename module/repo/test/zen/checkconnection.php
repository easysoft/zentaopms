#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkConnection();
timeout=0
cid=0

- 步骤1：空POST数据边界验证，测试方法对无参数调用的处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 4. 精确的7个测试步骤，提升测试覆盖率和代码质量

r($repoTest->checkConnectionTest()) && p() && e('0'); // 步骤1：空POST数据边界验证，测试方法对无参数调用的处理

r($repoTest->checkConnectionTest(array(
    'SCM' => 'InvalidSCM',
    'client' => 'invalid',
    'path' => '/test/path'
))) && p() && e('0'); // 步骤2：无效SCM类型验证，测试方法对不支持SCM类型的处理

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'testuser',
    'password' => 'testpass',
    'encoding' => 'UTF-8',
    'path' => 'https://invalid-svn.example.com/repo'
))) && p() && e('0'); // 步骤3：Subversion连接失败测试，验证SVN仓库连接验证逻辑

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/nonexistent/git/repo'
))) && p() && e('0'); // 步骤4：Git不存在目录验证，测试本地Git仓库路径有效性检查

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitlab',
    'client' => '',
    'account' => 'gitlab-user',
    'password' => 'token123',
    'encoding' => 'UTF-8',
    'path' => 'https://gitlab.example.com/group/project.git'
))) && p() && e('1'); // 步骤5：Gitlab绕过检查测试，验证Gitlab类型的特殊处理逻辑

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitea',
    'name' => 'test-repo',
    'serviceProject' => '',
    'serviceHost' => '1'
))) && p() && e('0'); // 步骤6：Gitea参数验证测试，检查serviceProject必要参数

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gogs',
    'name' => '',
    'serviceProject' => '456',
    'serviceHost' => '2'
))) && p() && e('0'); // 步骤7：Gogs参数验证测试，检查name必要参数的完整性