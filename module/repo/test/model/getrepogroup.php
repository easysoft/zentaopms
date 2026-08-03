#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getRepoGroup();
timeout=0
cid=18076

- 执行repoTest模块的getRepoGroupIsArrayTest方法，参数是$type  @1
- 执行repoTest模块的getRepoGroupTest方法，参数是$type 第4条的text属性 @正常产品4
- 执行repoTest模块的getRepoGroupItemsTest方法，参数是$type, 0, 1 第0条的text属性 @testHtml
- 执行repoTest模块的getRepoGroupCountTest方法，参数是$type, $projectID  @0
- 执行repoTest模块的getRepoGroupCountTest方法，参数是$type, $projectID  @1
- 执行repoTest模块的getRepoGroupCountTest方法，参数是$type, 0  @4

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

zenData('projectproduct')->gen(0);
$projectProductTable = zenData('projectproduct');
$projectProductTable->project->range('11');
$projectProductTable->product->range('1');
$projectProductTable->branch->range('0');
$projectProductTable->plan->range('');
$projectProductTable->roadmap->range('');
$projectProductTable->gen(1);

zenData('product')->gen(0);
$productTable = zenData('product');
$productTable->id->range('1-4');
$productTable->name->range('正常产品1,正常产品2,正常产品3,正常产品4');
$productTable->code->range('product1,product2,product3,product4');
$productTable->shadow->range('0{4}');
$productTable->deleted->range('0{4}');
$productTable->gen(4);

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

$repo       = $tester->loadModel('repo');
$repoTest   = new repoModelTest();
$repoTest->seedGitFoxEntry();

$type      = 'project';
$projectID = 1;

r($repoTest->getRepoGroupIsArrayTest($type)) && p() && e('1');
r($repoTest->getRepoGroupTest($type)) && p('4:text') && e('正常产品4');
r($repoTest->getRepoGroupItemsTest($type, 0, 1)) && p('0:text') && e('testHtml');
r($repoTest->getRepoGroupCountTest($type, $projectID)) && p() && e('0');

$projectID = 11;
r($repoTest->getRepoGroupCountTest($type, $projectID)) && p() && e('1');
r($repoTest->getRepoGroupCountTest($type, 0)) && p() && e('4');
