#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getSCM();
timeout=0
cid=18144

- 步骤1：正常的GitLab服务器类型 @Gitlab
- 步骤2：正常的Gitea服务器类型 @Gitea
- 步骤3：正常的Gogs服务器类型 @Gogs
- 步骤4：正常的Git服务器类型 @Git
- 步骤5：正常的Subversion服务器类型 @Subversion
- 步骤6：不存在的服务器ID @0
- 步骤7：无效的字符串服务器ID @0
- 步骤8：未知的服务器类型 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 手动创建测试数据以避免重复键问题
global $tester;
$tester->dao->delete()->from(TABLE_PIPELINE)->exec();

// 插入测试数据
$tester->dao->insert(TABLE_PIPELINE)->data(array(
    'id' => 1,
    'type' => 'gitlab',
    'name' => 'GitLab服务器',
    'url' => 'https://gitlab.example.com',
    'account' => 'admin',
    'password' => 'password123',
    'token' => 'token_gitlab_123',
    'createdBy' => 'admin',
    'createdDate' => '2023-01-01 00:00:00',
    'deleted' => '0'
))->exec();

$tester->dao->insert(TABLE_PIPELINE)->data(array(
    'id' => 4,
    'type' => 'gitea',
    'name' => 'Gitea服务器',
    'url' => 'https://gitea.example.com',
    'account' => 'admin',
    'password' => 'password123',
    'token' => 'token_gitea_456',
    'createdBy' => 'admin',
    'createdDate' => '2023-01-01 00:00:00',
    'deleted' => '0'
))->exec();

$tester->dao->insert(TABLE_PIPELINE)->data(array(
    'id' => 6,
    'type' => 'gogs',
    'name' => 'Gogs服务器',
    'url' => 'https://gogs.example.com',
    'account' => 'admin',
    'password' => 'password123',
    'token' => 'token_gogs_789',
    'createdBy' => 'admin',
    'createdDate' => '2023-01-01 00:00:00',
    'deleted' => '0'
))->exec();

$tester->dao->insert(TABLE_PIPELINE)->data(array(
    'id' => 8,
    'type' => 'git',
    'name' => 'Git仓库',
    'url' => '/path/to/git',
    'account' => 'admin',
    'password' => 'password123',
    'createdBy' => 'admin',
    'createdDate' => '2023-01-01 00:00:00',
    'deleted' => '0'
))->exec();

$tester->dao->insert(TABLE_PIPELINE)->data(array(
    'id' => 9,
    'type' => 'subversion',
    'name' => 'SVN服务器',
    'url' => 'svn://svn.example.com',
    'account' => 'admin',
    'password' => 'password123',
    'createdBy' => 'admin',
    'createdDate' => '2023-01-01 00:00:00',
    'deleted' => '0'
))->exec();

$tester->dao->insert(TABLE_PIPELINE)->data(array(
    'id' => 10,
    'type' => 'unknown',
    'name' => '未知服务器',
    'url' => 'https://unknown.example.com',
    'account' => 'admin',
    'password' => 'password123',
    'createdBy' => 'admin',
    'createdDate' => '2023-01-01 00:00:00',
    'deleted' => '0'
))->exec();

su('admin');

$repoTest = new repoZenTest();

r($repoTest->getSCMTest(1)) && p() && e('Gitlab');               // 步骤1：正常的GitLab服务器类型
r($repoTest->getSCMTest(4)) && p() && e('Gitea');                // 步骤2：正常的Gitea服务器类型
r($repoTest->getSCMTest(6)) && p() && e('Gogs');                 // 步骤3：正常的Gogs服务器类型
r($repoTest->getSCMTest(8)) && p() && e('Git');                  // 步骤4：正常的Git服务器类型
r($repoTest->getSCMTest(9)) && p() && e('Subversion');           // 步骤5：正常的Subversion服务器类型
r($repoTest->getSCMTest(999)) && p() && e('0');                   // 步骤6：不存在的服务器ID
r($repoTest->getSCMTest('invalid')) && p() && e('0');             // 步骤7：无效的字符串服务器ID
r($repoTest->getSCMTest(10)) && p() && e('0');                    // 步骤8：未知的服务器类型