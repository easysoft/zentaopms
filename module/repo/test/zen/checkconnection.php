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
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoTest();

// 4. 测试步骤：必须包含至少5个测试步骤

r($repoTest->checkConnectionZenTest(array())) && p() && e('0'); // 步骤1：空POST数据

r($repoTest->checkConnectionZenTest(array(
    'SCM' => 'Subversion',
    'client' => 'svn',
    'account' => 'test',
    'password' => 'test123',
    'encoding' => 'UTF-8',
    'path' => 'file:///tmp/svn/test'
))) && p() && e('1'); // 步骤2：Subversion连接检查（本地文件系统）

r($repoTest->checkConnectionZenTest(array(
    'SCM' => 'Git',
    'client' => 'git',
    'account' => '',
    'password' => '',
    'encoding' => 'UTF-8',
    'path' => '/tmp/git/test'
))) && p() && e('0'); // 步骤3：Git连接检查（目录不存在）

r($repoTest->checkConnectionZenTest(array(
    'SCM' => 'Gitea',
    'name' => 'test-repo',
    'serviceProject' => '1',
    'serviceHost' => '1'
))) && p() && e('1'); // 步骤4：Gitea连接检查

r($repoTest->checkConnectionZenTest(array(
    'SCM' => 'Gogs',
    'name' => 'test-repo',
    'serviceProject' => '1',
    'serviceHost' => '1'
))) && p() && e('1'); // 步骤5：Gogs连接检查