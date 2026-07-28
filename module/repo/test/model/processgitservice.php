#!/usr/bin/env php
<?php

/**
title=测试 repoModel::processGitService();
timeout=0
cid=18089

- 步骤1：正常处理Gitlab版本库
- 步骤1：正常处理 Gitlab 版本库
 - 属性client @http://localhost:3000
 - 属性codePath @http://liyang.oop.cc:3000/git/test1/test0909.git
- 步骤2：正常处理 GitFox 版本库
 - 属性codePath @http://liyang.oop.cc:3000/git/test1/test120101.git
 - 属性name @repo4
- 步骤3：处理另一个 Gitlab 版本库
 - 属性serviceProject @1
 - 属性client @http://localhost:3000
- 步骤4：带 codePath 参数处理版本库1
 - 属性client @http://localhost:3000
 - 属性apiPath @http://localhost:3000/api/v2/repos/1
- 步骤5：空 serviceHost 时保留 GitFox 服务信息
 - 属性serviceHost @0
 - 属性client @http://localhost:3000
- 步骤6：无效 path 会被远端仓库地址覆盖
 - 属性path @http://liyang.oop.cc:3000/git/test1/test0909.git
 - 属性codePath @http://liyang.oop.cc:3000/git/test1/test0909.git

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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
  `serviceProject` varchar(255) NOT NULL DEFAULT '',
  `serviceHost` varchar(50) NOT NULL DEFAULT '',
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

$repoTable = zenData('ops_repo');
$repoTable->id->range('1,2,4');
$repoTable->spaceID->range('1');
$repoTable->product->range('1');
$repoTable->name->range('repo1,repo2,repo4');
$repoTable->path->range('http://repo.local/repo1,http://repo.local/repo2,/tmp/repo4');
$repoTable->SCM->range('Gitlab,Gitlab,GitFox');
$repoTable->scmType->range('git');
$repoTable->serviceProject->range('2,1,4');
$repoTable->serviceHost->range('1');
$repoTable->gitUID->range('uid1,uid2,uid4');
$repoTable->acl->range('private');
$repoTable->status->range('active');
$repoTable->deleted->range('0');
$repoTable->gen(3);

$repoUserTable = zenData('ops_repouser');
$repoUserTable->repo->range('1,2,4');
$repoUserTable->account->range('admin');
$repoUserTable->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repo = new repoModelTest();
$repo->seedGitFoxEntry();
$repo->instance->config->devops->gitfoxURL  = 'http://localhost';
$repo->instance->config->devops->gitfoxPort = 3000;
$repo->setGitfoxRepoCache(1, (object)array('id' => 1, 'path' => 'test1/test0909',  'gitURL' => 'http://liyang.oop.cc:3000/git/test1/test0909.git',  'importing' => false));
$repo->setGitfoxRepoCache(2, (object)array('id' => 2, 'path' => 'test1/test0202',  'gitURL' => 'http://liyang.oop.cc:3000/git/test1/test0202.git',  'importing' => false));
$repo->setGitfoxRepoCache(4, (object)array('id' => 4, 'path' => 'test1/test120101','gitURL' => 'http://liyang.oop.cc:3000/git/test1/test120101.git','importing' => false));

// 5. 测试步骤
r($repo->processGitServiceTest(1)) && p('client,codePath') && e('http://localhost:3000,http://liyang.oop.cc:3000/git/test1/test0909.git');
r($repo->processGitServiceTest(4)) && p('codePath,name') && e('http://liyang.oop.cc:3000/git/test1/test120101.git,repo4');
r($repo->processGitServiceTest(2)) && p('serviceProject,client') && e('1,http://localhost:3000');
r($repo->processGitServiceTestWithCodePath(1)) && p('client,apiPath') && e('http://localhost:3000,http://localhost:3000/api/v2/repos/1/');
r($repo->processGitServiceTestWithEmptyHost(4)) && p('serviceHost,client') && e('0,http://localhost:3000');
r($repo->processGitServiceTestWithInvalidPath(1)) && p('path,codePath') && e('http://liyang.oop.cc:3000/git/test1/test0909.git,http://liyang.oop.cc:3000/git/test1/test0909.git');
