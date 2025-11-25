#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkConnection();
timeout=0
cid=18131

- 步骤1：空POST数据边界验证 @0
- 步骤2：无效SCM类型验证 @0
- 步骤3：Subversion客户端为空验证 @0
- 步骤4：Subversion路径为空验证 @0
- 步骤5：Git不存在目录验证 @0
- 步骤6：Gitlab绕过检查测试 @1
- 步骤7：Gitea缺少name参数验证 @0
- 步骤8：Gogs缺少serviceProject参数验证 @0
- 步骤9：Subversion版本检查失败验证 @0
- 步骤10：Git权限受限目录验证 @0
- 步骤11：编码转换测试 @0
- 步骤12：Gitea完整参数但API失败验证 @0
- 步骤13：Gogs完整参数但API失败验证 @0
- 步骤14：Subversion文件协议测试 @0
- 步骤15：Git命令执行失败测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 4. 强制要求：必须包含至少5个测试步骤，这里扩展为15个测试步骤提升覆盖率
r($repoTest->checkConnectionTest()) && p() && e('0'); // 步骤1：空POST数据边界验证

$testData1 = array(
    'SCM' => 'InvalidSCM',
    'client' => 'invalid',
    'path' => '/test/path'
);
r($repoTest->checkConnectionTest($testData1)) && p() && e('0'); // 步骤2：无效SCM类型验证

$testData2 = array(
    'SCM' => 'Subversion',
    'client' => '',
    'account' => 'testuser',
    'password' => 'testpass',
    'path' => 'https://svn.example.com/repo'
);
r($repoTest->checkConnectionTest($testData2)) && p() && e('0'); // 步骤3：Subversion客户端为空验证

$testData3 = array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'testuser',
    'password' => 'testpass',
    'path' => ''
);
r($repoTest->checkConnectionTest($testData3)) && p() && e('0'); // 步骤4：Subversion路径为空验证

$testData4 = array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/nonexistent/git/repo'
);
r($repoTest->checkConnectionTest($testData4)) && p() && e('0'); // 步骤5：Git不存在目录验证

$testData5 = array(
    'SCM' => 'Gitlab',
    'client' => '',
    'account' => 'gitlab-user',
    'password' => 'token123',
    'encoding' => 'UTF-8',
    'path' => 'https://gitlab.example.com/group/project.git'
);
r($repoTest->checkConnectionTest($testData5)) && p() && e('1'); // 步骤6：Gitlab绕过检查测试

$testData6 = array(
    'SCM' => 'Gitea',
    'name' => '',
    'serviceProject' => '123',
    'serviceHost' => '1'
);
r($repoTest->checkConnectionTest($testData6)) && p() && e('0'); // 步骤7：Gitea缺少name参数验证

$testData7 = array(
    'SCM' => 'Gogs',
    'name' => 'test-repo',
    'serviceProject' => '',
    'serviceHost' => '2'
);
r($repoTest->checkConnectionTest($testData7)) && p() && e('0'); // 步骤8：Gogs缺少serviceProject参数验证

$testData8 = array(
    'SCM' => 'Subversion',
    'client' => 'git',
    'account' => 'testuser',
    'password' => 'testpass',
    'path' => 'https://svn.example.com/repo'
);
r($repoTest->checkConnectionTest($testData8)) && p() && e('0'); // 步骤9：Subversion版本检查失败验证

$testData9 = array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/root'
);
r($repoTest->checkConnectionTest($testData9)) && p() && e('0'); // 步骤10：Git权限受限目录验证

$testData10 = array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'testuser',
    'password' => 'testpass',
    'encoding' => 'GBK',
    'path' => 'https://svn.example.com/repo'
);
r($repoTest->checkConnectionTest($testData10)) && p() && e('0'); // 步骤11：编码转换测试

$testData11 = array(
    'SCM' => 'Gitea',
    'name' => 'test-repo',
    'serviceProject' => '123',
    'serviceHost' => '1'
);
r($repoTest->checkConnectionTest($testData11)) && p() && e('0'); // 步骤12：Gitea完整参数但API失败验证

$testData12 = array(
    'SCM' => 'Gogs',
    'name' => 'test-repo',
    'serviceProject' => '456',
    'serviceHost' => '2'
);
r($repoTest->checkConnectionTest($testData12)) && p() && e('0'); // 步骤13：Gogs完整参数但API失败验证

$testData13 = array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'testuser',
    'password' => 'testpass',
    'path' => 'file:///local/svn/repo'
);
r($repoTest->checkConnectionTest($testData13)) && p() && e('0'); // 步骤14：Subversion文件协议测试

$testData14 = array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/tmp'
);
r($repoTest->checkConnectionTest($testData14)) && p() && e('0'); // 步骤15：Git命令执行失败测试