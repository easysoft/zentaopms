#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getPairs();
timeout=0
cid=0

- 步骤1：返回未删除且非导入中的仓库总数 @3
- 步骤2：返回结果包含正常仓库键值对 @repo1,repo4,repo5
- 步骤3：导入中的仓库不会出现在结果中 @0
- 步骤4：已删除仓库不会出现在结果中 @0
- 步骤5：可正常读取指定仓库名称 @repo4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $lang, $dbh, $app;
if(!isset($lang->codescan)) $lang->codescan = new stdclass();
if(!isset($lang->codescan->exec)) $lang->codescan->exec = 'exec';
if(!isset($lang->codescan->issue)) $lang->codescan->issue = 'issue';

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

r(count($repoTest->getPairsTest()))    && p()        && e('3');
r($repoTest->getPairsTest())           && p('1,4,5') && e('repo1,repo4,repo5');
r(isset($repoTest->getPairsTest()[2])) && p()        && e('0');
r(isset($repoTest->getPairsTest()[3])) && p()        && e('0');
r($repoTest->getPairsTest())           && p('4')     && e('repo4');
