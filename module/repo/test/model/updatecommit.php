#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

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

zenData('ops_repobranch')->gen(0);

$repoTable = zenData('ops_repo');
$repoTable->id->range('1-3');
$repoTable->spaceID->range('1');
$repoTable->product->range('1');
$repoTable->name->range('GitlabRepo,GitRepo,SVNRepo');
$repoTable->path->range('/tmp/gitlab,/tmp/git,/tmp/svn');
$repoTable->SCM->range('Gitlab,Git,Subversion');
$repoTable->scmType->range('git,git,svn');
$repoTable->gitUID->range('uid1,uid2,uid3');
$repoTable->acl->range('open');
$repoTable->status->range('active');
$repoTable->deleted->range('0');
$repoTable->gen(3);

$branchTable = zenData('ops_repobranch');
$branchTable->repo->range('2');
$branchTable->revision->range('1');
$branchTable->branch->range('main');
$branchTable->gen(1);

$repoTest = new repoModelTest();

r($repoTest->updateCommitTest(1)) && p('SCM,status') && e('Gitlab,success');
r($repoTest->updateCommitTest(2)) && p('SCM,status') && e('Git,success');
r($repoTest->updateCommitTest(3)) && p('SCM,status') && e('Subversion,success');
r($repoTest->updateCommitTest(999)) && p('repoID,status') && e('999,repoNotFound');
r($repoTest->updateCommitTest(0)) && p('repoID,status') && e('0,repoNotFound');
$_COOKIE['repoBranch'] = 'main';
$repoTest->instance->cookie->repoBranch = 'main';
r($repoTest->updateCommitTest(2, 0, 'main')) && p('SCM,branchID,status') && e('Git,main,success');
r($repoTest->updateCommitTest(2, 123)) && p('SCM,objectID,status') && e('Git,123,success');
