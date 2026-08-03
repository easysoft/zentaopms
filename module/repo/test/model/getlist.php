#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getList();
timeout=0
cid=18069

- 执行repo模块的getListTest方法，参数是0, 0, 'id_asc' 第1条的name属性 @testHtml
- 执行repo模块的getListCountTest方法，参数是0, 0, 'id_asc'  @4
- 执行repo模块的getListTest方法，参数是0, 0, 'id_asc' 第4条的name属性 @testSvn
- 执行repo模块的getListTest方法，参数是0, 0, 'id_asc' 第3条的name属性 @unittest
- 执行repo模块的getListCountTest方法，参数是0, 0, 'id_asc'  @4

*/

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_provider`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_spaceuser`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint(1) NOT NULL DEFAULT 0,
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_provider` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(30) NOT NULL DEFAULT '',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_spaceuser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `space` int unsigned NOT NULL DEFAULT 0,
  `role` varchar(10) NOT NULL DEFAULT '',
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

zenData('ops_space')->gen(0);
$spaceTable = zenData('ops_space');
$spaceTable->id->range('1');
$spaceTable->name->range('repo-test-space');
$spaceTable->code->range('repo-test-space');
$spaceTable->acl->range('open');
$spaceTable->auth->range('extend');
$spaceTable->deleted->range('0');
$spaceTable->gen(1);

$repoTable = zenData('ops_repo');
$repoTable->id->range('1-4');
$repoTable->spaceID->range('1{4}');
$repoTable->product->range('1,2,3,4');
$repoTable->name->range('testHtml,project1,unittest,testSvn');
$repoTable->scmType->range('git,git,git,svn');
$repoTable->gitUID->range('uid1,uid2,uid3,uid4');
$repoTable->providerID->range('0{4}');
$repoTable->mirror->range('0{4}');
$repoTable->acl->range('open{4}');
$repoTable->status->range('active{4}');
$repoTable->deleted->range('0{4}');
$repoTable->gen(4);

$spaceUserTable = zenData('ops_spaceuser');
$spaceUserTable->space->range('1');
$spaceUserTable->role->range('manager');
$spaceUserTable->account->range('admin');
$spaceUserTable->gen(1);

su('admin');

$repo = new repoModelTest();
$repo->seedGitFoxEntry();

r($repo->getListTest(0, 0, 'id_asc')) && p('1:name') && e('testHtml');
r($repo->getListCountTest(0, 0, 'id_asc')) && p() && e('4');
r($repo->getListTest(0, 0, 'id_asc')) && p('4:name') && e('testSvn');
r($repo->getListTest(0, 0, 'id_asc')) && p('3:name') && e('unittest');
r($repo->getListCountTest(0, 0, 'id_asc')) && p() && e('4');
