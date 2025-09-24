#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkConnection();
timeout=0
cid=0

- 步骤1：空POST数据验证 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 4. 测试步骤：15个测试步骤，全面覆盖不同场景

r($repoTest->checkConnectionTest()) && p() && e('0'); // 步骤1：空POST数据验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'InvalidSCM',
    'client' => 'invalid',
    'path' => '/test/path'
))) && p() && e('0'); // 步骤2：无效SCM类型验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'testuser',
    'password' => 'testpass',
    'encoding' => 'UTF-8',
    'path' => 'https://invalid-svn.example.com/repo'
))) && p() && e('0'); // 步骤3：Subversion协议无效路径

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/nonexistent/git/repo'
))) && p() && e('0'); // 步骤4：Git目录不存在验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitlab',
    'client' => '',
    'account' => 'gitlab-user',
    'password' => 'token123',
    'encoding' => 'UTF-8',
    'path' => 'https://gitlab.example.com/group/project.git'
))) && p() && e('1'); // 步骤5：Gitlab连接绕过检查

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitea',
    'name' => 'test-repo',
    'serviceProject' => '',
    'serviceHost' => '1'
))) && p() && e('0'); // 步骤6：Gitea缺少必要参数

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gogs',
    'name' => '',
    'serviceProject' => '456',
    'serviceHost' => '2'
))) && p() && e('0'); // 步骤7：Gogs缺少必要参数

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'test用户',
    'password' => 'pass密码',
    'encoding' => 'GBK',
    'path' => 'file:///nonexistent/中文路径'
))) && p() && e('0'); // 步骤8：Subversion编码转换测试

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'path' => '/root'  // 权限受限目录
))) && p() && e('0'); // 步骤9：Git路径访问权限测试

r($repoTest->checkConnectionTest(array(
    'SCM' => null,
    'client' => null,
    'path' => null
))) && p() && e('0'); // 步骤10：异常参数边界值测试

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => '',
    'path' => 'svn://localhost/repo'
))) && p() && e('0'); // 步骤11：SVN客户端为空验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'path' => ''
))) && p() && e('0'); // 步骤12：Git路径为空验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitea',
    'name' => 'test-repo',
    'serviceProject' => '123',
    'serviceHost' => ''
))) && p() && e('0'); // 步骤13：Gitea服务主机为空验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'invalid-svn',
    'account' => 'user',
    'password' => 'pass',
    'path' => 'file:///test/repo'
))) && p() && e('0'); // 步骤14：SVN客户端工具无效验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'path' => '/etc/passwd'  // 文件而非目录
))) && p() && e('0'); // 步骤15：Git路径为文件验证