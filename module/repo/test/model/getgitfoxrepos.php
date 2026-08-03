#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getGitFoxRepos();
timeout=0
cid=0

- 执行repoTest模块的getGitFoxReposTest方法 属性1 @repo1
- 执行repoTest模块的getGitFoxReposTest方法 属性2 @repo2
- 执行repoTest模块的getGitFoxReposTest方法 属性3 @repo3
- 执行repoTest模块的getGitFoxReposTest方法
 - 属性4 @~~
 - 属性5 @~~
- 执行repoTest模块的getGitFoxReposTest方法
 - 属性1 @repo1
 - 属性2 @repo2
 - 属性3 @repo3

*/

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int unsigned NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint unsigned NOT NULL DEFAULT 0,
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repo = zendata('ops_repo');
$repo->id->range('1-5');
$repo->spaceID->range('1{5}');
$repo->product->range('1{5}');
$repo->name->range('repo1,repo2,repo3,repo4,repo5');
$repo->scmType->range('git{5}');
$repo->gitUID->range('uid1,uid2,uid3,uid4,uid5');
$repo->providerID->setNull();
$repo->mirror->setNull();
$repo->acl->range('open{5}');
$repo->status->range('active{3},importing,active');
$repo->deleted->range('0{4},1');
$repo->gen(5);

$repoTest = new repoModelTest();
$repoTest->seedGitFoxEntry();
r($repoTest->getGitFoxReposTest()) && p('1')     && e('repo1');
r($repoTest->getGitFoxReposTest()) && p('2')     && e('repo2');
r($repoTest->getGitFoxReposTest()) && p('3')     && e('repo3');
r($repoTest->getGitFoxReposTest()) && p('4,5')   && e('~~,~~');
r($repoTest->getGitFoxReposTest()) && p('1,2,3') && e('repo1,repo2,repo3');
