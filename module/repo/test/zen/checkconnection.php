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

// 4. 强制要求：必须包含至少5个测试步骤，这里扩展为13个测试步骤提升覆盖率
r($repoTest->checkConnectionTest()) && p() && e('0'); // 步骤1：空POST数据边界验证，测试方法对无参数调用的处理

r($repoTest->checkConnectionTest(array(
    'SCM' => 'InvalidSCM',
    'client' => 'invalid',
    'path' => '/test/path'
))) && p() && e('0'); // 步骤2：无效SCM类型验证，测试方法对不支持SCM类型的处理

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => '',
    'account' => 'testuser',
    'password' => 'testpass',
    'path' => 'https://svn.example.com/repo'
))) && p() && e('0'); // 步骤3：Subversion客户端为空验证，测试SVN客户端必填验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'testuser',
    'password' => 'testpass',
    'path' => ''
))) && p() && e('0'); // 步骤4：Subversion路径为空验证，测试SVN路径必填验证

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/nonexistent/git/repo'
))) && p() && e('0'); // 步骤5：Git不存在目录验证，测试本地Git仓库路径有效性检查

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitlab',
    'client' => '',
    'account' => 'gitlab-user',
    'password' => 'token123',
    'encoding' => 'UTF-8',
    'path' => 'https://gitlab.example.com/group/project.git'
))) && p() && e('1'); // 步骤6：Gitlab绕过检查测试，验证Gitlab类型的特殊处理逻辑

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitea',
    'name' => '',
    'serviceProject' => '123',
    'serviceHost' => '1'
))) && p() && e('0'); // 步骤7：Gitea缺少name参数验证，检查name必要参数

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gogs',
    'name' => 'test-repo',
    'serviceProject' => '',
    'serviceHost' => '2'
))) && p() && e('0'); // 步骤8：Gogs缺少serviceProject参数验证，检查serviceProject必要参数

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'git',
    'account' => 'testuser',
    'password' => 'testpass',
    'path' => 'https://svn.example.com/repo'
))) && p() && e('0'); // 步骤9：Subversion无效客户端验证，测试非SVN客户端的拒绝处理

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/root'
))) && p() && e('0'); // 步骤10：Git权限受限目录验证，测试访问权限受限目录的处理

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'testuser',
    'password' => 'testpass',
    'encoding' => 'GBK',
    'path' => 'https://svn.example.com/repo'
))) && p() && e('0'); // 步骤11：编码转换测试，验证非UTF-8编码的路径处理

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gitea',
    'name' => 'test-repo',
    'serviceProject' => '123',
    'serviceHost' => '1'
))) && p() && e('0'); // 步骤12：Gitea完整参数但API失败验证，检查API连接失败处理

r($repoTest->checkConnectionTest(array(
    'SCM' => 'Gogs',
    'name' => 'test-repo',
    'serviceProject' => '456',
    'serviceHost' => '2'
))) && p() && e('0'); // 步骤13：Gogs完整参数但API失败验证，检查API连接失败处理