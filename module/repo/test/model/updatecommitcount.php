#!/usr/bin/env php
<?php

/**

title=测试 repoModel::updateCommitCount();
timeout=0
cid=18111

- 测试步骤1：正常更新版本库提交计数
 - 属性id @1
 - 属性commits @100
- 测试步骤2：更新提交计数为0
 - 属性id @2
 - 属性commits @0
- 测试步骤3：更新提交计数为极大值
 - 属性id @3
 - 属性commits @999999
- 测试步骤4：更新不存在的版本库ID @0
- 测试步骤5：更新另一个版本库的提交计数
 - 属性id @4
 - 属性commits @1000

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $dbh;
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(500) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `projects` varchar(255) NOT NULL DEFAULT '',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `forkID` int unsigned DEFAULT NULL,
  `mirror` tinyint(1) NOT NULL DEFAULT 0,
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `connector` text DEFAULT NULL,
  `defaultBranch` varchar(255) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime DEFAULT NULL,
  `deleted` tinyint NOT NULL DEFAULT 0,
  `synced` tinyint unsigned NOT NULL DEFAULT 0,
  `branchArchivable` tinyint NOT NULL DEFAULT 0,
  `commits` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repo = zenData('ops_repo');
$repo->id->range('1-5');
$repo->spaceID->range('1{5}');
$repo->product->range('1{5}');
$repo->name->range('repo1,repo2,repo3,repo4,repo5');
$repo->gitUID->range('uid1,uid2,uid3,uid4,uid5');
$repo->status->range('active{5}');
$repo->deleted->range('0{5}');
$repo->commits->range('1,2,3,4,5');
$repo->gen(5);

$repoTest = new repoModelTest();

r($repoTest->updateCommitCountTest(1, 100))   && p('id,commits') && e('1,100');
r($repoTest->updateCommitCountTest(2, 0))     && p('id,commits') && e('2,0');
r($repoTest->updateCommitCountTest(3, 999999)) && p('id,commits') && e('3,999999');
r($repoTest->updateCommitCountTest(999, 50))  && p()             && e('0');
r($repoTest->updateCommitCountTest(4, 1000))  && p('id,commits') && e('4,1000');
