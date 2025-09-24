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

// 4. 强制要求：必须包含至少5个测试步骤，当前为15个测试步骤全面覆盖不同场景

r($repoTest->checkConnectionTest()) && p() && e('0'); // 步骤1：空POST数据验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'InvalidSCM',
    'client' => 'invalid',
    'path' => '/test/path'
))) && p() && e('0'); // 步骤2：无效SCM类型验证，测试非标准SCM类型

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'testuser',
    'password' => 'testpass',
    'encoding' => 'UTF-8',
    'path' => 'https://invalid-svn.example.com/repo'
))) && p() && e('0'); // 步骤3：Subversion HTTPS协议无效路径连接测试

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/nonexistent/git/repo'
))) && p() && e('0'); // 步骤4：Git不存在目录验证，测试文件系统路径有效性

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitlab',
    'client' => '',
    'account' => 'gitlab-user',
    'password' => 'token123',
    'encoding' => 'UTF-8',
    'path' => 'https://gitlab.example.com/group/project.git'
))) && p() && e('1'); // 步骤5：Gitlab类型绕过检查测试，验证Gitlab特殊处理逻辑

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitea',
    'name' => 'test-repo',
    'serviceProject' => '',
    'serviceHost' => '1'
))) && p() && e('0'); // 步骤6：Gitea缺少serviceProject必要参数验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gogs',
    'name' => '',
    'serviceProject' => '456',
    'serviceHost' => '2'
))) && p() && e('0'); // 步骤7：Gogs缺少name必要参数验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'test用户',
    'password' => 'pass密码',
    'encoding' => 'GBK',
    'path' => 'file:///nonexistent/中文路径'
))) && p() && e('0'); // 步骤8：Subversion GBK编码转换测试，验证非UTF-8编码处理

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'path' => '/root'  // 权限受限目录
))) && p() && e('0'); // 步骤9：Git权限受限目录访问测试，验证目录权限检查

r($repoTest->checkConnectionTest(array(
    'SCM' => null,
    'client' => null,
    'path' => null
))) && p() && e('0'); // 步骤10：异常null参数边界值测试，验证空值处理健壮性

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => '',
    'path' => 'svn://localhost/repo'
))) && p() && e('0'); // 步骤11：Subversion空客户端验证，测试必填字段检查

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'path' => ''
))) && p() && e('0'); // 步骤12：Git空路径验证，测试路径必填字段检查

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitea',
    'name' => 'test-repo',
    'serviceProject' => '123',
    'serviceHost' => ''
))) && p() && e('0'); // 步骤13：Gitea空服务主机验证，测试serviceHost必填检查

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'invalid-svn',
    'account' => 'user',
    'password' => 'pass',
    'path' => 'file:///test/repo'
))) && p() && e('0'); // 步骤14：Subversion无效客户端工具验证，测试客户端可执行性

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'path' => '/etc/passwd'  // 文件而非目录
))) && p() && e('0'); // 步骤15：Git文件路径而非目录验证，测试路径类型检查