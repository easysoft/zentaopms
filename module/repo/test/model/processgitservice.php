#!/usr/bin/env php
<?php

/**

title=测试 repoModel::processGitService();
timeout=0
cid=18089

- 步骤1：正常处理Gitlab版本库
 - 属性client @http://gitfox.local:3000
 - 属性codePath @http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml
- 步骤2：正常处理Gitea版本库
 - 属性codePath @/var/www/html/gitlab/test2/zentaopms/www/data/repo/unittest_gitea
 - 属性name @unittest
- 步骤3：处理另一个Gitlab版本库属性serviceProject @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_repouser`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `serviceProject` varchar(255) NOT NULL DEFAULT '',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'private',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repouser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$tester->dao->delete()->from(TABLE_ENTRY)->where('code')->eq('gitfox')->exec();
$tester->dao->insert(TABLE_ENTRY)->data((object)array(
    'name'        => 'GitFox入口',
    'account'     => 'admin',
    'code'        => 'gitfox',
    'key'         => 'testkey1234567890testkey1234567',
    'freePasswd'  => 0,
    'ip'          => '*',
    'createdBy'   => 'admin',
    'createdDate' => '2026-01-01 00:00:00',
    'calledTime'  => 0,
    'editedBy'    => 'admin',
    'editedDate'  => '2026-01-01 00:00:00',
    'deleted'     => 0,
))->exec();

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'testHtml', 'path' => 'http://repo.local/testhtml', 'SCM' => 'Gitlab', 'scmType' => 'git', 'serviceProject' => '2', 'gitUID' => 'uid1', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => 'project1', 'path' => 'http://repo.local/project1', 'SCM' => 'Gitlab', 'scmType' => 'git', 'serviceProject' => '1', 'gitUID' => 'uid2', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'unittest', 'path' => '/tmp/unittest', 'SCM' => 'GitFox', 'scmType' => 'git', 'serviceProject' => 'gitea/unittest', 'gitUID' => 'uid3', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData) $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();
foreach(range(1, 3) as $repoID) $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoID, 'account' => 'admin'))->exec();

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repo = new repoModelTest();
$repo->instance->config->devops->gitfoxURL  = 'http://gitfox.local';
$repo->instance->config->devops->gitfoxPort = 3000;
$repo->setGitfoxRepoCache(1, (object)array('id' => 1, 'path' => 'space/testhtml', 'gitURL' => 'http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml'));
$repo->setGitfoxRepoCache(2, (object)array('id' => 2, 'path' => 'space/project1', 'gitURL' => 'http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/Monitoring'));
$repo->setGitfoxRepoCache(3, (object)array('id' => 3, 'path' => 'space/unittest', 'gitURL' => '/var/www/html/gitlab/test2/zentaopms/www/data/repo/unittest_gitea'));

// 5. 强制要求：必须包含至少7个测试步骤
r($repo->processGitServiceTest(1)) && p('client,codePath') && e('http://gitfox.local:3000,http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml'); // 步骤1：正常处理Gitlab版本库
r($repo->processGitServiceTest(3)) && p('codePath,name') && e('/var/www/html/gitlab/test2/zentaopms/www/data/repo/unittest_gitea,unittest'); // 步骤2：正常处理Gitea版本库
r($repo->processGitServiceTest(2)) && p('serviceProject') && e('1'); // 步骤3：处理另一个Gitlab版本库
