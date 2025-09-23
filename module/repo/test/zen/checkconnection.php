#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkConnection();
timeout=0
cid=0

- 步骤1：空POST数据 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 4. 测试步骤：必须包含至少10个测试步骤

r($repoTest->checkConnectionTest()) && p() && e('0'); // 步骤1：空POST数据

r($repoTest->checkConnectionTest(array(
    'SCM' => 'InvalidSCM',
    'client' => 'invalid',
    'path' => '/test/path'
))) && p() && e('0'); // 步骤2：无效SCM类型

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'testuser',
    'password' => 'testpass',
    'encoding' => 'UTF-8',
    'path' => 'https://svn.example.com/repo'
))) && p() && e('0'); // 步骤3：Subversion HTTPS连接（命令不存在）

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'testuser',
    'password' => 'testpass',
    'encoding' => 'UTF-8',
    'path' => 'file:///nonexistent/svn/repo'
))) && p() && e('0'); // 步骤4：Subversion文件协议（路径不存在）

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/nonexistent/git/repo'
))) && p() && e('0'); // 步骤5：Git路径不存在

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/tmp'
))) && p() && e('0'); // 步骤6：Git路径存在但非仓库

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitlab',
    'client' => '',
    'account' => 'gitlab-user',
    'password' => 'token123',
    'encoding' => 'UTF-8',
    'path' => 'https://gitlab.example.com/group/project.git'
))) && p() && e('1'); // 步骤7：Gitlab连接（绕过检查）

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitea',
    'name' => 'test-repo',
    'serviceProject' => '123',
    'serviceHost' => '1'
))) && p() && e('0'); // 步骤8：Gitea API连接

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gogs',
    'name' => 'test-repo',
    'serviceProject' => '456',
    'serviceHost' => '2'
))) && p() && e('0'); // 步骤9：Gogs API连接

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'test用户',
    'password' => 'pass密码',
    'encoding' => 'GBK',
    'path' => 'file:///repo/中文路径'
))) && p() && e('0'); // 步骤10：编码转换测试