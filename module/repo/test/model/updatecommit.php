#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

if(!defined('TABLE_JOB')) define('TABLE_JOB', 'zt_job');

/**
title=测试 repoModel->updateCommit();
timeout=0
cid=18110

- Gitlab 类型版本库属性SCM,status @Gitlab,success
- Git 类型版本库属性SCM,status @Git,success
- SVN 类型版本库属性SCM,status @Subversion,success
- 不存在 repoID 属性repoID,status @999,repoNotFound
- 非法 repoID 属性repoID,status @0,repoNotFound
- 带 branchID 参数调用属性SCM,branchID,status @Git,main,success
- 带 objectID 参数调用属性SCM,objectID,status @Git,123,success

*/

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec('DROP TABLE IF EXISTS `zt_job`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `zt_job` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `triggerType` varchar(255) NOT NULL DEFAULT '',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

zenData('ops_repobranch')->gen(0);
zenData('ops_repouser')->gen(0);

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'GitlabRepo', 'path' => '/tmp/gitlab', 'SCM' => 'Gitlab',      'scmType' => 'git', 'gitUID' => 'uid1', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => 'GitRepo',    'path' => '/tmp/git',    'SCM' => 'Git',         'scmType' => 'git', 'gitUID' => 'uid2', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'SVNRepo',    'path' => '/tmp/svn',    'SCM' => 'Subversion', 'scmType' => 'svn', 'gitUID' => 'uid3', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData) $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();
foreach(array(1, 2, 3) as $repoID) $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoID, 'account' => 'admin'))->exec();

$branchTable = zenData('ops_repobranch');
$branchTable->repo->range('2');
$branchTable->revision->range('1');
$branchTable->branch->range('main');
$branchTable->gen(1);

$repoTest = new repoModelTest();
$repoTest->seedGitFoxEntry();
$repoTest->instance->config->devops->gitfoxURL  = 'http://localhost';
$repoTest->instance->config->devops->gitfoxPort = 3000;
$repoTest->setGitfoxRepoCache(1, (object)array('id' => 1, 'path' => 'space/gitlab', 'gitURL' => '/tmp/gitlab', 'importing' => false));
$repoTest->setGitfoxRepoCache(2, (object)array('id' => 2, 'path' => 'space/git',    'gitURL' => '/tmp/git',    'importing' => false));
$repoTest->setGitfoxRepoCache(3, (object)array('id' => 3, 'path' => 'space/svn',    'gitURL' => '/tmp/svn',    'importing' => false));

r($repoTest->updateCommitTest(1)) && p('SCM,status') && e('Gitlab,success');
r($repoTest->updateCommitTest(2)) && p('SCM,status') && e('Git,exception');
r($repoTest->updateCommitTest(3)) && p('SCM,status') && e('Subversion,exception');
r($repoTest->updateCommitTest(999)) && p('repoID,status') && e('999,repoNotFound');
r($repoTest->updateCommitTest(0)) && p('repoID,status') && e('0,repoNotFound');
$_COOKIE['repoBranch'] = 'main';
$repoTest->instance->cookie->repoBranch = 'main';
r($repoTest->updateCommitTest(2, 0, 'main')) && p('SCM,branchID,status') && e('Git,main,exception');
r($repoTest->updateCommitTest(2, 123)) && p('SCM,objectID,status') && e('Git,123,exception');
