#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getByID();
timeout=0
cid=18048

- 测试步骤1：正常获取存在的repo对象
 - 属性id @1
 - 属性name @testHtml
 - 属性SCM @Gitlab
- 测试步骤2：验证repo对象的基本属性属性serviceProject @1
- 测试步骤3：测试不存在的repoID @0
- 测试步骤4：测试无效的repoID(0) @0
- 测试步骤5：测试负数repoID @0
- 测试步骤6：验证Gitea仓库信息
 - 属性name @unittest
 - 属性SCM @Gitea
- 测试步骤7：验证SVN仓库加密信息
 - 属性account @admin
 - 属性encrypt @base64

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repouser`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `client` varchar(255) NOT NULL DEFAULT '',
  `serviceHost` varchar(255) NOT NULL DEFAULT '',
  `serviceProject` varchar(255) NOT NULL DEFAULT '',
  `extra` varchar(255) NOT NULL DEFAULT '',
  `projects` varchar(255) NOT NULL DEFAULT '',
  `commits` int NOT NULL DEFAULT 0,
  `account` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `encrypt` varchar(30) NOT NULL DEFAULT 'base64',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'private',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  `synced` tinyint unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint(1) NOT NULL DEFAULT 0,
  `lastCommit` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repouser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'testHtml',  'path' => 'http://repo.local/testhtml', 'SCM' => 'Subversion', 'scmType' => 'git', 'serviceHost' => '1', 'serviceProject' => '2', 'extra' => '9', 'projects' => '11', 'gitUID' => 'uid1', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => 'project1',  'path' => 'http://repo.local/project1', 'SCM' => 'Subversion', 'scmType' => 'git', 'serviceHost' => '1', 'serviceProject' => '1', 'extra' => '9', 'projects' => '12', 'gitUID' => 'uid2', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'unittest',  'path' => 'http://repo.local/unittest', 'SCM' => 'Subversion', 'scmType' => 'git', 'serviceHost' => '4', 'serviceProject' => 'gitea/unittest', 'extra' => '9', 'projects' => '13', 'commits' => 2, 'gitUID' => 'uid3', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '1', 'name' => 'testSvn',   'path' => 'https://svn.qc.oop.cc/svn/unittest/', 'SCM' => 'Subversion', 'scmType' => 'svn', 'account' => 'admin', 'password' => 'encoded', 'encrypt' => 'base64', 'projects' => '14', 'gitUID' => 'uid4', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repo) $tester->dao->insert(TABLE_REPO)->data((object)$repo)->exec();

foreach(range(1, 4) as $repoID)
{
    $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoID, 'account' => 'admin'))->exec();
}

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();
$repoTest->seedGitFoxEntry();

r($repoTest->getByIDTest(1)) && p('id,name,SCM') && e('1,testHtml,Subversion'); // 测试步骤1：正常获取存在的repo对象
r($repoTest->getByIDTest(2)) && p('serviceProject') && e('1'); // 测试步骤2：验证repo对象的基本属性
r($repoTest->getByIDTest(999)) && p() && e('0'); // 测试步骤3：测试不存在的repoID
r($repoTest->getByIDTest(0)) && p() && e('0'); // 测试步骤4：测试无效的repoID(0)
r($repoTest->getByIDTest(-1)) && p() && e('0'); // 测试步骤5：测试负数repoID
r($repoTest->getByIDTest(3)) && p('name,SCM') && e('unittest,Subversion'); // 测试步骤6：验证Gitea仓库信息
r($repoTest->getByIDTest(4)) && p('account,encrypt') && e('admin,base64'); // 测试步骤7：验证SVN仓库加密信息
