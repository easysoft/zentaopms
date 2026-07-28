#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**
title=测试 repoModel::getBranches();
timeout=0
cid=18047

- 步骤1：从SCM获取repo1分支 @0
- 步骤2：从SCM获取repo1分支带标签 @0
- 步骤3：从数据库获取repo1分支属性master,develop @master,develop
- 步骤4：数据库分支带标签前缀属性master,develop @Branch::master,Branch::develop
- 步骤5：不存在repo测试 @0
- 步骤6：repo2数据库分支测试属性master,release @master,release
- 步骤7：repo2带标签测试属性master,release @Branch::master,Branch::release

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
  `acl` varchar(30) NOT NULL DEFAULT 'private',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
zenData('ops_repouser')->gen(0);
zenData('ops_repobranch')->gen(0);

$repoTable = zenData('ops_repo');
$repoTable->id->range('1,2');
$repoTable->spaceID->range('1');
$repoTable->product->range('1');
$repoTable->name->range('repo1,repo2');
$repoTable->path->range('/tmp/repo1,/tmp/repo2');
$repoTable->SCM->range('Git');
$repoTable->scmType->range('git');
$repoTable->gitUID->range('uid1,uid2');
$repoTable->acl->range('private');
$repoTable->status->range('active');
$repoTable->deleted->range('0');
$repoTable->gen(2);

$repoUserTable = zenData('ops_repouser');
$repoUserTable->repo->range('1,2');
$repoUserTable->account->range('admin');
$repoUserTable->gen(2);

$branchTable = zenData('ops_repobranch');
$branchTable->repo->range('1,1,2,2');
$branchTable->revision->range('1,2,3,4');
$branchTable->branch->range('master,develop,master,release');
$branchTable->gen(4);

su('admin');

$repoTest = new repoModelTest();

r($repoTest->getBranchesTest(1, false, 'scm')) && p() && e('0');
r($repoTest->getBranchesTest(1, true, 'scm')) && p() && e('0');
r($repoTest->getBranchesTest(1, false, 'database')) && p('master,develop') && e('master,develop');
r($repoTest->getBranchesTest(1, true, 'database')) && p('master,develop') && e('Branch::master,Branch::develop');
r($repoTest->getBranchesTest(999, false, 'scm')) && p() && e('0');
r($repoTest->getBranchesTest(2, false, 'database')) && p('master,release') && e('master,release');
r($repoTest->getBranchesTest(2, true, 'database')) && p('master,release') && e('Branch::master,Branch::release');
