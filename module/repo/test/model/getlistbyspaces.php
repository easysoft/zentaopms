#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getListBySpaces();
timeout=0
cid=0

- 步骤1：只查询单个空间时返回该空间下有效仓库数量 @1
- 步骤2：空间 3 下的第一个有效仓库可被取到 @1
- 步骤3：空间 3 下的第二个有效仓库可被取到 @1
- 步骤4：仅有已删除仓库的空间返回空结果 @0
- 步骤5：查询多个空间时会合并所有有效仓库 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $lang, $app;
if(!isset($lang->codescan)) $lang->codescan = new stdclass();
if(!isset($lang->codescan->exec)) $lang->codescan->exec = 'exec';
if(!isset($lang->codescan->issue)) $lang->codescan->issue = 'issue';

$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repo = zenData('ops_repo');
$repo->id->range('1-5');
$repo->spaceID->range('1,1,2,3,3');
$repo->product->range('1{5}');
$repo->name->range('repo1,repo2,repo3,repo4,repo5');
$repo->gitUID->range('uid1,uid2,uid3,uid4,uid5');
$repo->status->range('active,importing,active,active,active');
$repo->deleted->range('0,0,1,0,0');
$repo->gen(5);

$repoTest = new repoModelTest();

r($repoTest->getListBySpacesCountTest(array(1))) && p() && e('1');
r($repoTest->getListBySpacesHasKeyTest(array(3), 4)) && p() && e('1');
r($repoTest->getListBySpacesHasKeyTest(array(3), 5)) && p() && e('1');
r($repoTest->getListBySpacesCountTest(array(2))) && p() && e('0');
r($repoTest->getListBySpacesCountTest(array(1, 3))) && p() && e('3');
